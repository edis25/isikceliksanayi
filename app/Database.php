<?php
/**
 * Işık Çelik — PDO veritabanı katmanı.
 * Lokalde SQLite, üretimde MySQL ile çalışır; config.php üzerinden seçilir.
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;
    private string $driver;

    private function __construct(array $cfg)
    {
        $this->driver = $cfg['driver'] ?? 'sqlite';

        if ($this->driver === 'mysql') {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $cfg['host'] ?? 'localhost',
                $cfg['name'] ?? ''
            );
            $this->pdo = new PDO($dsn, $cfg['user'] ?? '', $cfg['pass'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ]);
        } else {
            $path = $cfg['sqlite_path'] ?? __DIR__ . '/../data/site.db';
            $dir = dirname($path);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $this->pdo = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $this->pdo->exec('PRAGMA foreign_keys = ON');
        }
    }

    public static function init(array $cfg): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database($cfg);
        }
        return self::$instance;
    }

    public static function get(): Database
    {
        if (self::$instance === null) {
            throw new RuntimeException('Database not initialized');
        }
        return self::$instance;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function all(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function row(string $sql, array $params = []): ?array
    {
        $r = $this->query($sql, $params)->fetch();
        return $r === false ? null : $r;
    }

    public function value(string $sql, array $params = [])
    {
        $r = $this->query($sql, $params)->fetchColumn();
        return $r === false ? null : $r;
    }

    public function insert(string $table, array $data): int
    {
        $cols = array_keys($data);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $cols),
            implode(', ', array_map(fn($c) => ':' . $c, $cols))
        );
        $this->query($sql, $data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): void
    {
        $sets = implode(', ', array_map(fn($c) => "$c = :$c", array_keys($data)));
        $this->query("UPDATE $table SET $sets WHERE $where", array_merge($data, $whereParams));
    }

    public function delete(string $table, string $where, array $params = []): void
    {
        $this->query("DELETE FROM $table WHERE $where", $params);
    }
}
