<?php
/**
 * Işık Çelik — kurulum betiği.
 * Veritabanı şemasını oluşturur ve başlangıç içeriğini yükler.
 *
 * Kullanım:
 *   CLI   : php install.php [--fresh]
 *   Web   : /install.php?key=isik-kurulum  (kurulumdan sonra bu dosyayı SİLİN!)
 */

require_once __DIR__ . '/app/helpers.php';

$isCli = PHP_SAPI === 'cli';
if (!$isCli && (($_GET['key'] ?? '') !== 'isik-kurulum')) {
    http_response_code(403);
    exit('Erişim reddedildi.');
}

app_boot();
$db  = Database::get();
$pdo = $db->pdo();
$driver = $db->driver();

$fresh = $isCli ? in_array('--fresh', $argv ?? [], true) : isset($_GET['fresh']);

$pk     = $driver === 'mysql' ? 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
$suffix = $driver === 'mysql' ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci' : '';

$tables = [
    'settings' => "CREATE TABLE IF NOT EXISTS settings (
        id $pk,
        skey VARCHAR(100) NOT NULL UNIQUE,
        label VARCHAR(190) DEFAULT '',
        value_tr TEXT,
        value_en TEXT
    )$suffix",

    'users' => "CREATE TABLE IF NOT EXISTS users (
        id $pk,
        username VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        name VARCHAR(150) DEFAULT '',
        created_at VARCHAR(25) DEFAULT ''
    )$suffix",

    'pages' => "CREATE TABLE IF NOT EXISTS pages (
        id $pk,
        pkey VARCHAR(50) NOT NULL UNIQUE,
        template VARCHAR(50) NOT NULL,
        slug_tr VARCHAR(190) NOT NULL,
        slug_en VARCHAR(190) NOT NULL,
        title_tr VARCHAR(190) DEFAULT '',
        title_en VARCHAR(190) DEFAULT '',
        meta_title_tr VARCHAR(190) DEFAULT '',
        meta_title_en VARCHAR(190) DEFAULT '',
        meta_desc_tr TEXT,
        meta_desc_en TEXT,
        image VARCHAR(255) DEFAULT '',
        sort_order INT DEFAULT 0,
        is_published INT DEFAULT 1
    )$suffix",

    'sections' => "CREATE TABLE IF NOT EXISTS sections (
        id $pk,
        page_id INT NOT NULL,
        skey VARCHAR(60) NOT NULL,
        type VARCHAR(30) DEFAULT 'text',
        sort_order INT DEFAULT 0,
        title_tr VARCHAR(255) DEFAULT '',
        title_en VARCHAR(255) DEFAULT '',
        subtitle_tr TEXT,
        subtitle_en TEXT,
        body_tr TEXT,
        body_en TEXT,
        data_json TEXT,
        image VARCHAR(255) DEFAULT '',
        is_published INT DEFAULT 1
    )$suffix",

    'categories' => "CREATE TABLE IF NOT EXISTS categories (
        id $pk,
        slug_tr VARCHAR(190) NOT NULL,
        slug_en VARCHAR(190) NOT NULL,
        name_tr VARCHAR(190) NOT NULL,
        name_en VARCHAR(190) DEFAULT '',
        sort_order INT DEFAULT 0,
        is_published INT DEFAULT 1
    )$suffix",

    'products' => "CREATE TABLE IF NOT EXISTS products (
        id $pk,
        category_id INT DEFAULT 0,
        slug_tr VARCHAR(190) NOT NULL,
        slug_en VARCHAR(190) NOT NULL,
        name_tr VARCHAR(190) NOT NULL,
        name_en VARCHAR(190) DEFAULT '',
        summary_tr TEXT,
        summary_en TEXT,
        body_tr TEXT,
        body_en TEXT,
        spec_table TEXT,
        image VARCHAR(255) DEFAULT '',
        gallery TEXT,
        meta_title_tr VARCHAR(190) DEFAULT '',
        meta_title_en VARCHAR(190) DEFAULT '',
        meta_desc_tr TEXT,
        meta_desc_en TEXT,
        sort_order INT DEFAULT 0,
        is_published INT DEFAULT 1
    )$suffix",

    'sectors' => "CREATE TABLE IF NOT EXISTS sectors (
        id $pk,
        name_tr VARCHAR(190) NOT NULL,
        name_en VARCHAR(190) DEFAULT '',
        desc_tr TEXT,
        desc_en TEXT,
        icon VARCHAR(50) DEFAULT '',
        image VARCHAR(255) DEFAULT '',
        sort_order INT DEFAULT 0,
        is_published INT DEFAULT 1
    )$suffix",

    'news' => "CREATE TABLE IF NOT EXISTS news (
        id $pk,
        slug_tr VARCHAR(190) NOT NULL,
        slug_en VARCHAR(190) NOT NULL,
        title_tr VARCHAR(255) NOT NULL,
        title_en VARCHAR(255) DEFAULT '',
        summary_tr TEXT,
        summary_en TEXT,
        body_tr TEXT,
        body_en TEXT,
        image VARCHAR(255) DEFAULT '',
        meta_title_tr VARCHAR(190) DEFAULT '',
        meta_title_en VARCHAR(190) DEFAULT '',
        meta_desc_tr TEXT,
        meta_desc_en TEXT,
        published_at VARCHAR(25) DEFAULT '',
        is_published INT DEFAULT 1,
        created_at VARCHAR(25) DEFAULT ''
    )$suffix",

    'messages' => "CREATE TABLE IF NOT EXISTS messages (
        id $pk,
        name VARCHAR(150) DEFAULT '',
        email VARCHAR(190) DEFAULT '',
        phone VARCHAR(50) DEFAULT '',
        subject VARCHAR(190) DEFAULT '',
        message TEXT,
        lang VARCHAR(5) DEFAULT 'tr',
        ip VARCHAR(64) DEFAULT '',
        is_read INT DEFAULT 0,
        created_at VARCHAR(25) DEFAULT ''
    )$suffix",

    'media' => "CREATE TABLE IF NOT EXISTS media (
        id $pk,
        filename VARCHAR(255) NOT NULL,
        path VARCHAR(255) NOT NULL,
        type VARCHAR(100) DEFAULT '',
        size INT DEFAULT 0,
        created_at VARCHAR(25) DEFAULT ''
    )$suffix",
];

$out = static function (string $msg) use ($isCli): void {
    echo $isCli ? $msg . PHP_EOL : e($msg) . '<br>';
};

if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<meta charset="utf-8"><body style="font-family:monospace;padding:2rem">';
}

if ($fresh) {
    foreach (array_keys($tables) as $t) {
        $pdo->exec("DROP TABLE IF EXISTS $t");
    }
    $out('Mevcut tablolar silindi (fresh).');
}

foreach ($tables as $name => $sql) {
    $pdo->exec($sql);
    $out("Tablo hazır: $name");
}

/* ---------- Seed ---------- */
$already = (int) $db->value('SELECT COUNT(*) FROM pages');
if ($already > 0) {
    $out('İçerik zaten yüklü, seed atlandı. (Sıfırlamak için --fresh / ?fresh=1 kullanın)');
} else {
    require __DIR__ . '/app/seed.php';
    seed_database($db);
    $out('Başlangıç içeriği yüklendi (sayfalar, bölümler, ürünler, sektörler, haberler, ayarlar).');
}

/* ---------- Yönetici kullanıcı ---------- */
$adminCount = (int) $db->value('SELECT COUNT(*) FROM users');
if ($adminCount === 0) {
    $password = 'IsikCelik!2026';
    $db->insert('users', [
        'username'      => 'admin',
        'password_hash' => password_hash($password, PASSWORD_BCRYPT),
        'name'          => 'Yönetici',
        'created_at'    => date('Y-m-d H:i:s'),
    ]);
    $out('Yönetici oluşturuldu — kullanıcı adı: admin, parola: ' . $password);
    $out('!!! İlk girişten sonra parolayı mutlaka değiştirin.');
}

$out('Kurulum tamamlandı. Üretim sunucusunda bu dosyayı (install.php) silmeyi unutmayın!');
