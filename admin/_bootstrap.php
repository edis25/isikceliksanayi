<?php
/**
 * Admin ortak başlatma: oturum, kimlik doğrulama, yardımcılar.
 */
require_once __DIR__ . '/../app/helpers.php';
app_boot();
session_boot();

if (!defined('LANG')) {
    define('LANG', 'tr');
}

/** Giriş yapılmış mı? */
function admin_user(): ?array
{
    return $_SESSION['admin_user'] ?? null;
}

/** Giriş zorunluluğu — girilmemişse login'e yönlendirir. */
function admin_require(): void
{
    if (!admin_user()) {
        header('Location: login.php');
        exit;
    }
}

/** POST isteklerinde CSRF doğrulaması; başarısızsa 403. */
function admin_csrf_guard(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) {
        http_response_code(403);
        exit('Geçersiz istek (CSRF).');
    }
}

/** Bilgi mesajı (flash). */
function admin_flash(?string $set = null): string
{
    if ($set !== null) {
        $_SESSION['admin_flash'] = $set;
        return '';
    }
    $msg = $_SESSION['admin_flash'] ?? '';
    unset($_SESSION['admin_flash']);
    return $msg;
}

/** Panel sayfa başlığı + gezinme. */
function admin_header(string $title): void
{
    $user = admin_user();
    $current = basename($_SERVER['SCRIPT_NAME']);
    $nav = [
        'index.php'    => ['Panel', '⌂'],
        'pages.php'    => ['Sayfalar', '☰'],
        'products.php' => ['Ürünler', '▦'],
        'categories.php' => ['Kategoriler', '⊞'],
        'sectors.php'  => ['Sektörler', '◫'],
        'news.php'     => ['Haberler', '✎'],
        'media.php'    => ['Medya', '▣'],
        'messages.php' => ['Mesajlar', '✉'],
        'settings.php' => ['Ayarlar', '⚙'],
        'users.php'    => ['Kullanıcılar', '☺'],
    ];
    $unread = 0;
    if ($user) {
        $unread = (int) Database::get()->value('SELECT COUNT(*) FROM messages WHERE is_read = 0');
    }
    echo '<!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<meta name="robots" content="noindex, nofollow">';
    echo '<title>' . e($title) . ' — Işık Çelik Yönetim</title>';
    echo '<link rel="stylesheet" href="assets/admin.css"></head><body>';
    if ($user) {
        echo '<aside class="sidebar"><div class="side-brand">IŞIK ÇELİK<span>Yönetim Paneli</span></div><nav>';
        foreach ($nav as $file => [$label, $icon]) {
            $active = $current === $file ? ' class="active"' : '';
            $badge = ($file === 'messages.php' && $unread > 0) ? ' <span class="badge">' . $unread . '</span>' : '';
            echo "<a href=\"$file\"$active><span class=\"nav-ico\">$icon</span> $label$badge</a>";
        }
        echo '</nav><div class="side-foot"><a href="../" target="_blank">Siteyi Görüntüle ↗</a><a href="logout.php">Çıkış Yap</a></div></aside>';
    }
    echo '<main class="admin-main">';
    if ($user) {
        echo '<div class="admin-top"><h1>' . e($title) . '</h1>';
        echo '<span class="who">' . e($user['name'] ?: $user['username']) . '</span></div>';
    }
    if (($msg = admin_flash()) !== '') {
        echo '<div class="notice">' . e($msg) . '</div>';
    }
}

function admin_footer(): void
{
    // TR/EN sekme geçişleri
    echo <<<'HTML'
<script>
document.querySelectorAll('.lang-tabs').forEach(function (tabs) {
    var scope = tabs.parentElement;
    tabs.querySelectorAll('button').forEach(function (btn) {
        btn.addEventListener('click', function (ev) {
            ev.preventDefault();
            tabs.querySelectorAll('button').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            scope.querySelectorAll(':scope > .lang-pane').forEach(function (p) {
                p.classList.toggle('active', p.dataset.lang === btn.dataset.lang);
            });
        });
    });
});
</script>
HTML;
    echo '</main></body></html>';
}

/**
 * Görsel yükleme: uploads/YYYYMM/ altına kaydeder, büyükse 1920px'e küçültür.
 * Dönüş: kaydedilen göreli yol veya hata durumunda null.
 */
function admin_handle_upload(array $file, ?string &$error = null): ?string
{
    $error = null;
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $error = 'Dosya yüklenemedi (kod: ' . ($file['error'] ?? '?') . ').';
        return null;
    }
    if ($file['size'] > 15 * 1024 * 1024) {
        $error = 'Dosya 15MB sınırını aşıyor.';
        return null;
    }
    $info = @getimagesize($file['tmp_name']);
    $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    $isSvg = false;
    if ($info === false) {
        // SVG kontrolü
        $head = file_get_contents($file['tmp_name'], false, null, 0, 512);
        if (stripos($head, '<svg') !== false && stripos($head, '<script') === false) {
            $isSvg = true;
        } else {
            $error = 'Yalnızca JPG, PNG, WebP veya SVG yükleyebilirsiniz.';
            return null;
        }
    } elseif (!isset($allowed[$info[2]])) {
        $error = 'Yalnızca JPG, PNG, WebP veya SVG yükleyebilirsiniz.';
        return null;
    }

    $dir = 'uploads/' . date('Ym');
    $abs = __DIR__ . '/../' . $dir;
    if (!is_dir($abs)) {
        mkdir($abs, 0755, true);
    }
    $base = pathinfo($file['name'], PATHINFO_FILENAME);
    $ext  = $isSvg ? 'svg' : $allowed[$info[2]];
    $name = slugify($base) ?: 'gorsel';
    $name .= '-' . substr(bin2hex(random_bytes(4)), 0, 6) . '.' . $ext;
    $target = $abs . '/' . $name;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        $error = 'Dosya taşınamadı.';
        return null;
    }

    // Büyük görselleri küçült (GD varsa)
    if (!$isSvg && function_exists('imagecreatetruecolor') && $info[0] > 1920) {
        $srcImg = match ($info[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($target),
            IMAGETYPE_PNG  => imagecreatefrompng($target),
            IMAGETYPE_WEBP => imagecreatefromwebp($target),
            default        => null,
        };
        if ($srcImg) {
            $ratio = 1920 / $info[0];
            $nw = 1920;
            $nh = (int) round($info[1] * $ratio);
            $dst = imagecreatetruecolor($nw, $nh);
            if ($info[2] === IMAGETYPE_PNG) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }
            imagecopyresampled($dst, $srcImg, 0, 0, 0, 0, $nw, $nh, $info[0], $info[1]);
            match ($info[2]) {
                IMAGETYPE_JPEG => imagejpeg($dst, $target, 82),
                IMAGETYPE_PNG  => imagepng($dst, $target, 8),
                IMAGETYPE_WEBP => imagewebp($dst, $target, 82),
            };
            imagedestroy($srcImg);
            imagedestroy($dst);
        }
    }

    $rel = $dir . '/' . $name;
    Database::get()->insert('media', [
        'filename'   => $name,
        'path'       => $rel,
        'type'       => $isSvg ? 'image/svg+xml' : ($info['mime'] ?? ''),
        'size'       => (int) filesize($target),
        'created_at' => date('Y-m-d H:i:s'),
    ]);
    return $rel;
}

/** Görsel seçim alanı (metin girişi + mevcut medya listesi + önizleme). */
function admin_image_field(string $name, string $value, string $label = 'Görsel'): void
{
    static $listPrinted = false;
    $db = Database::get();
    if (!$listPrinted) {
        echo '<datalist id="media-list">';
        foreach ($db->all('SELECT path FROM media ORDER BY id DESC LIMIT 200') as $m) {
            echo '<option value="' . e($m['path']) . '">';
        }
        foreach (glob(__DIR__ . '/../assets/img/*.{jpg,png,webp}', GLOB_BRACE) ?: [] as $f) {
            echo '<option value="assets/img/' . e(basename($f)) . '">';
        }
        echo '</datalist>';
        $listPrinted = true;
    }
    echo '<div class="field"><label>' . e($label) . '</label>';
    echo '<div class="img-pick">';
    echo '<input type="text" name="' . e($name) . '" value="' . e($value) . '" list="media-list" placeholder="assets/img/... veya uploads/...">';
    if ($value) {
        echo '<img class="img-prev" src="../' . e($value) . '" alt="">';
    }
    echo '</div><small>Medya sayfasından yükleyip yolu buraya yapıştırabilirsiniz.</small></div>';
}
