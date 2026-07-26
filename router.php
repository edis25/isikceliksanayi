<?php
/**
 * Lokal geliştirme yönlendiricisi (php -S localhost:8080 router.php).
 * .htaccess davranışını taklit eder: gerçek dosyalar sunulur, kalan her şey index.php'ye gider.
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    // /admin/*.php gibi PHP dosyalarını çalıştır, statikleri sun
    if (str_ends_with($file, '.php')) {
        require $file;
        return true;
    }
    return false;
}
if ($path !== '/' && is_dir($file) && is_file($file . '/index.php')) {
    require $file . '/index.php';
    return true;
}

require __DIR__ . '/index.php';
