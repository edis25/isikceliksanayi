<?php
/**
 * Dinamik sitemap.xml — tüm sayfalar, ürünler ve haberler (TR + EN, hreflang ile).
 * index.php üzerinden /sitemap.xml adresinde sunulur.
 */
if (!function_exists('app_boot')) {
    require_once __DIR__ . '/app/helpers.php';
    app_boot();
}
if (!defined('LANG')) {
    define('LANG', 'tr');
}

$db = Database::get();

$urls = [];
$addUrl = function (string $slugTr, string $slugEn, string $lastmod = '', string $priority = '0.7') use (&$urls) {
    $urls[] = [
        'tr' => url($slugTr, 'tr'),
        'en' => url($slugEn, 'en'),
        'lastmod' => $lastmod,
        'priority' => $priority,
    ];
};

$pages = $db->all('SELECT * FROM pages WHERE is_published = 1 ORDER BY sort_order');
$productsPage = null;
$newsPage = null;
foreach ($pages as $p) {
    if ($p['pkey'] === 'products') { $productsPage = $p; }
    if ($p['pkey'] === 'news') { $newsPage = $p; }
    $addUrl($p['slug_tr'], $p['slug_en'], '', $p['pkey'] === 'home' ? '1.0' : '0.8');
}

if ($productsPage) {
    foreach ($db->all('SELECT * FROM products WHERE is_published = 1') as $pr) {
        $addUrl(
            $productsPage['slug_tr'] . '/' . $pr['slug_tr'],
            $productsPage['slug_en'] . '/' . $pr['slug_en'],
            '', '0.7'
        );
    }
}
if ($newsPage) {
    foreach ($db->all('SELECT * FROM news WHERE is_published = 1') as $n) {
        $addUrl(
            $newsPage['slug_tr'] . '/' . $n['slug_tr'],
            $newsPage['slug_en'] . '/' . $n['slug_en'],
            $n['published_at'], '0.6'
        );
    }
}

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
foreach ($urls as $u) {
    foreach (['tr', 'en'] as $l) {
        echo "  <url>\n";
        echo '    <loc>' . e($u[$l]) . "</loc>\n";
        echo '    <xhtml:link rel="alternate" hreflang="tr" href="' . e($u['tr']) . "\"/>\n";
        echo '    <xhtml:link rel="alternate" hreflang="en" href="' . e($u['en']) . "\"/>\n";
        echo '    <xhtml:link rel="alternate" hreflang="x-default" href="' . e($u['tr']) . "\"/>\n";
        if ($u['lastmod']) {
            echo '    <lastmod>' . e($u['lastmod']) . "</lastmod>\n";
        }
        echo '    <priority>' . e($u['priority']) . "</priority>\n";
        echo "  </url>\n";
    }
}
echo '</urlset>';
