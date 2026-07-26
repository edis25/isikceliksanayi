<?php
/**
 * Işık Çelik — front controller / router.
 * Tüm istekler .htaccess üzerinden bu dosyaya yönlenir.
 */

require_once __DIR__ . '/app/helpers.php';
app_boot();

$db = Database::get();

/* ---------- İstek yolunu çözümle ---------- */
$uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$path = trim(rawurldecode($uri), '/');

/* sitemap & robots */
if ($path === 'sitemap.xml') {
    require __DIR__ . '/sitemap.php';
    exit;
}

/* Dili belirle: /en öneki İngilizce, kök Türkçe */
$langCode = 'tr';
if ($path === 'en' || str_starts_with($path, 'en/')) {
    $langCode = 'en';
    $path = trim(substr($path, 2), '/');
}
define('LANG', $langCode);

$slugField = 'slug_' . LANG;

/* ---------- Sayfaları yükle ---------- */
$allPages = $db->all('SELECT * FROM pages WHERE is_published = 1 ORDER BY sort_order, id');
$pagesByKey  = [];
$pagesBySlug = [];
foreach ($allPages as $p) {
    $pagesByKey[$p['pkey']] = $p;
    $pagesBySlug[$p[$slugField]] = $p;
}

/**
 * Bir sayfanın diğer dildeki URL'sini üretir (hreflang + dil değiştirici).
 * $extra: detay sayfalarında alt slug (ürün/haber) eklemek için [tr => ..., en => ...].
 */
function build_alternates(array $page, array $extra = []): array
{
    $alts = [];
    foreach (['tr', 'en'] as $l) {
        $slug = $page['slug_' . $l];
        if (!empty($extra)) {
            $slug .= '/' . ($extra[$l] ?? $extra['tr']);
        }
        $alts[$l] = url($slug, $l);
    }
    return $alts;
}

/* ---------- Rotayı eşleştir ---------- */
$page       = null;
$template   = '404';
$alternates = [];
$ctx        = []; // şablona geçecek ek veriler

if ($path === '') {
    $page = $pagesByKey['home'] ?? null;
    if ($page) {
        $template = $page['template'];
        $alternates = ['tr' => url('', 'tr'), 'en' => url('', 'en')];
    }
} elseif (isset($pagesBySlug[$path])) {
    $page = $pagesBySlug[$path];
    $template = $page['template'];
    $alternates = build_alternates($page);
} else {
    // İki segmentli dinamik rotalar: urunler/{slug}, haberler/{slug}
    $segments = explode('/', $path);
    if (count($segments) === 2) {
        [$parentSlug, $childSlug] = $segments;
        $parent = $pagesBySlug[$parentSlug] ?? null;
        if ($parent && $parent['pkey'] === 'products') {
            $product = $db->row(
                "SELECT * FROM products WHERE $slugField = :s AND is_published = 1",
                ['s' => $childSlug]
            );
            if ($product) {
                $page = $parent;
                $template = 'product-detail';
                $ctx['product'] = $product;
                $alternates = build_alternates($parent, ['tr' => $product['slug_tr'], 'en' => $product['slug_en']]);
            }
        } elseif ($parent && $parent['pkey'] === 'news') {
            $article = $db->row(
                "SELECT * FROM news WHERE $slugField = :s AND is_published = 1",
                ['s' => $childSlug]
            );
            if ($article) {
                $page = $parent;
                $template = 'news-detail';
                $ctx['article'] = $article;
                $alternates = build_alternates($parent, ['tr' => $article['slug_tr'], 'en' => $article['slug_en']]);
            }
        }
    }
}

/* ---------- Şablonu çalıştır ---------- */
if (!$page || !is_file(__DIR__ . '/templates/page-' . $template . '.php')) {
    http_response_code(404);
    $template = '404';
    $page = null;
    $alternates = ['tr' => url('', 'tr'), 'en' => url('', 'en')];
}

$sections = $page ? get_sections((int) $page['id']) : [];

/* SEO varsayılanları — şablonlar detay sayfalarında üzerine yazabilir */
$seo = [
    'title'       => $page ? (lv($page, 'meta_title') ?: lv($page, 'title')) : t('notfound.title'),
    'description' => $page ? lv($page, 'meta_desc') : '',
    'image'       => $page && !empty($page['image']) ? upload_url($page['image']) : asset('assets/img/og-default.jpg'),
    'canonical'   => $alternates[LANG] ?? url($path),
    'type'        => 'website',
];

require __DIR__ . '/templates/page-' . $template . '.php';
