<?php
/**
 * Işık Çelik — çekirdek yardımcı fonksiyonlar ve bootstrap.
 */

require_once __DIR__ . '/Database.php';

/** Uygulama yapılandırmasını döndürür. */
function app_config(): array
{
    static $config = null;
    if ($config === null) {
        $file = __DIR__ . '/config.php';
        if (!is_file($file)) {
            $file = __DIR__ . '/config.sample.php';
        }
        $config = require $file;
    }
    return $config;
}

/** Uygulamayı başlatır: hata modu, veritabanı, oturum. */
function app_boot(): void
{
    $cfg = app_config();
    if (($cfg['env'] ?? 'production') === 'local') {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', '0');
    }
    date_default_timezone_set('Europe/Istanbul');
    Database::init($cfg['db']);
}

/** HTML güvenli çıktı. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/** Aktif dil ('tr' | 'en'). Router tarafından tanımlanan LANG sabitini okur. */
function lang(): string
{
    return defined('LANG') ? LANG : 'tr';
}

/** Arayüz metni çevirisi. */
function t(string $key): string
{
    static $strings = null;
    if ($strings === null) {
        $strings = require __DIR__ . '/lang.php';
    }
    return $strings[lang()][$key] ?? $strings['tr'][$key] ?? $key;
}

/** Veritabanı satırından dile göre alan okur; boşsa TR'ye düşer. */
function lv(?array $row, string $field): string
{
    if (!$row) {
        return '';
    }
    $val = $row[$field . '_' . lang()] ?? '';
    if ($val === '' || $val === null) {
        $val = $row[$field . '_tr'] ?? '';
    }
    return (string) $val;
}

/** Site kök URL'si (sonda / olmadan). */
function base_url(): string
{
    static $base = null;
    if ($base === null) {
        $cfg = app_config();
        if (!empty($cfg['base_url'])) {
            $base = rtrim($cfg['base_url'], '/');
        } else {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $base = $scheme . '://' . $host;
        }
    }
    return $base;
}

/** Dil önekli site URL'si üretir. url('urunler/celik-profiller') gibi. */
function url(string $path = '', ?string $lang = null): string
{
    $lang = $lang ?? lang();
    $prefix = $lang === 'en' ? '/en' : '';
    $path = trim($path, '/');
    return base_url() . $prefix . ($path !== '' ? '/' . $path : ($lang === 'en' ? '/' : '/'));
}

/** Varlık (asset) URL'si. */
function asset(string $path): string
{
    return base_url() . '/' . ltrim($path, '/');
}

/** Yüklenen medya URL'si. */
function upload_url(?string $path): string
{
    if (!$path) {
        return '';
    }
    if (preg_match('#^https?://#', $path)) {
        return $path;
    }
    return base_url() . '/' . ltrim($path, '/');
}

/** Site ayarını dile göre döndürür. */
function setting(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (Database::get()->all('SELECT skey, value_tr, value_en FROM settings') as $r) {
            $cache[$r['skey']] = $r;
        }
    }
    if (!isset($cache[$key])) {
        return $default;
    }
    $val = lv($cache[$key], 'value');
    return $val !== '' ? $val : $default;
}

/** Yayında olan sayfayı anahtarına göre getirir. */
function get_page(string $pkey): ?array
{
    return Database::get()->row('SELECT * FROM pages WHERE pkey = :k AND is_published = 1', ['k' => $pkey]);
}

/** Sayfanın bölümlerini skey => satır olarak getirir. */
function get_sections(int $pageId): array
{
    $rows = Database::get()->all(
        'SELECT * FROM sections WHERE page_id = :p AND is_published = 1 ORDER BY sort_order, id',
        ['p' => $pageId]
    );
    $out = [];
    foreach ($rows as $r) {
        $r['data'] = $r['data_json'] ? (json_decode($r['data_json'], true) ?: []) : [];
        $out[$r['skey']] = $r;
    }
    return $out;
}

/** Bölümün data_json içinden dile göre liste döndürür (items_tr / items_en). */
function section_items(array $section): array
{
    $data = $section['data'] ?? [];
    $items = $data['items_' . lang()] ?? $data['items_tr'] ?? $data['items'] ?? [];
    return is_array($items) ? $items : [];
}

/** Satır sonlarını <p> paragraflarına çevirir (düz metin içerik için). */
function nl2p(?string $text): string
{
    $text = trim((string) $text);
    if ($text === '') {
        return '';
    }
    $parts = preg_split('/\R{2,}/u', $text);
    $html = '';
    foreach ($parts as $p) {
        $html .= '<p>' . nl2br(e(trim($p)), false) . '</p>' . "\n";
    }
    return $html;
}

/** Tarihi dile göre biçimlendirir. */
function format_date(?string $date): string
{
    if (!$date) {
        return '';
    }
    $ts = strtotime($date);
    if ($ts === false) {
        return '';
    }
    $monthsTr = [1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    if (lang() === 'tr') {
        return date('j', $ts) . ' ' . $monthsTr[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
    return date('F j, Y', $ts);
}

/** Türkçe karakter destekli slug üretir. */
function slugify(string $text): string
{
    $map = [
        'ç' => 'c', 'Ç' => 'c', 'ğ' => 'g', 'Ğ' => 'g', 'ı' => 'i', 'I' => 'i', 'İ' => 'i',
        'ö' => 'o', 'Ö' => 'o', 'ş' => 's', 'Ş' => 's', 'ü' => 'u', 'Ü' => 'u',
    ];
    $text = strtr($text, $map);
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/** Ürünün ilk galeri (fotoğraf) görselini döndürür — kart hover'ı için. */
function product_hover_image(array $product): ?string
{
    if (empty($product['gallery'])) {
        return null;
    }
    $g = json_decode($product['gallery'], true);
    return is_array($g) && !empty($g[0]) ? $g[0] : null;
}

/** Metni belirli uzunlukta kırpar. */
function excerpt(?string $text, int $len = 160): string
{
    $text = trim(strip_tags((string) $text));
    if (mb_strlen($text, 'UTF-8') <= $len) {
        return $text;
    }
    $cut = mb_substr($text, 0, $len, 'UTF-8');
    $pos = mb_strrpos($cut, ' ', 0, 'UTF-8');
    if ($pos !== false) {
        $cut = mb_substr($cut, 0, $pos, 'UTF-8');
    }
    return $cut . '…';
}

/* ---------- Oturum & CSRF ---------- */

function session_boot(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        ]);
        session_start();
    }
}

function csrf_token(): string
{
    session_boot();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_check(): bool
{
    session_boot();
    $sent = $_POST['_token'] ?? '';
    return is_string($sent) && $sent !== '' && hash_equals($_SESSION['csrf_token'] ?? '', $sent);
}
