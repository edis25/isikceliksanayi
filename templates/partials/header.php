<?php
require_once __DIR__ . '/icons.php';

/* Menü yapısı: kurumsal sayfalar tek başlık altında toplanır */
$corporateKeys = ['about', 'production', 'sustainability', 'industries', 'global'];
$topKeys = ['products', 'news', 'contact'];
$activeKey = $page['pkey'] ?? '';
$corporateActive = in_array($activeKey, $corporateKeys, true);

/* Açılır menüde "Kurumsal" sayfası "Hakkımızda" olarak etiketlenir */
$navLabel = function (array $p) {
    return $p['pkey'] === 'about' ? t('nav.aboutus') : lv($p, 'title');
};
$siteName = setting('site_name', 'Işık Çelik');
$metaSuffix = ' | ' . $siteName;
$fullTitle = $seo['title'] ?? $siteName;
if (mb_stripos($fullTitle, 'Işık Çelik') === false && mb_stripos($fullTitle, 'Isik') === false) {
    $fullTitle .= $metaSuffix;
}
$faviconSvg = rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><rect x="1.5" y="1.5" width="41" height="41" rx="8" fill="#1b2265"/><path d="M22 7 35 20 22 33 9 20Z" fill="#fff"/><path d="M13 27l9 8 9-8-2-2-7 6-7-6Z" fill="#fff"/></svg>');

$orgJsonLd = [
    '@context' => 'https://schema.org',
    '@type'    => 'Organization',
    'name'     => $siteName,
    'url'      => base_url() . '/',
    'logo'     => asset('assets/img/logo-dark.png'),
    'foundingDate' => '1965',
    'address'  => [
        '@type' => 'PostalAddress',
        'streetAddress'   => setting('address'),
        'addressLocality' => 'Karabük',
        'addressCountry'  => 'TR',
    ],
    'contactPoint' => [
        '@type'     => 'ContactPoint',
        'telephone' => setting('phone'),
        'email'     => setting('email'),
        'contactType' => 'sales',
        'availableLanguage' => ['Turkish', 'English'],
    ],
];
$sameAs = array_values(array_filter([setting('linkedin'), setting('instagram'), setting('youtube')]));
if ($sameAs) {
    $orgJsonLd['sameAs'] = $sameAs;
}
?><!DOCTYPE html>
<html lang="<?= e(lang()) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<?php if (!empty($seo['description'])): ?>
<meta name="description" content="<?= e($seo['description']) ?>">
<?php endif; ?>
<link rel="canonical" href="<?= e($seo['canonical']) ?>">
<?php foreach ($alternates as $l => $altUrl): ?>
<link rel="alternate" hreflang="<?= e($l) ?>" href="<?= e($altUrl) ?>">
<?php endforeach; ?>
<?php if (isset($alternates['tr'])): ?>
<link rel="alternate" hreflang="x-default" href="<?= e($alternates['tr']) ?>">
<?php endif; ?>
<meta property="og:site_name" content="<?= e($siteName) ?>">
<meta property="og:type" content="<?= e($seo['type'] ?? 'website') ?>">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:description" content="<?= e($seo['description'] ?? '') ?>">
<meta property="og:url" content="<?= e($seo['canonical']) ?>">
<meta property="og:image" content="<?= e($seo['image'] ?? '') ?>">
<meta property="og:locale" content="<?= lang() === 'tr' ? 'tr_TR' : 'en_US' ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($fullTitle) ?>">
<meta name="twitter:description" content="<?= e($seo['description'] ?? '') ?>">
<meta name="twitter:image" content="<?= e($seo['image'] ?? '') ?>">
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<?= $faviconSvg ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
<?php if (isset($_GET['_shot'])): /* tam sayfa ekran görüntüsü modu (geliştirme) */ ?>
<style>.hero{min-height:780px}.reveal{opacity:1 !important;transform:none !important}</style>
<?php endif; ?>
<script type="application/ld+json"><?= json_encode($orgJsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php if (!empty($jsonld_extra)): ?>
<script type="application/ld+json"><?= $jsonld_extra ?></script>
<?php endif; ?>
<?php if (($ga = setting('ga_code')) !== ''): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($ga) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e($ga) ?>');</script>
<?php endif; ?>
</head>
<body>
<header class="site-header<?= empty($transparentHeader) ? ' solid' : '' ?>">
    <div class="container">
        <a class="brand" href="<?= e(url('')) ?>" aria-label="<?= e($siteName) ?>">
            <img class="brand-logo" src="<?= e(asset('assets/img/logo-light.png')) ?>" alt="<?= e($siteName) ?>" width="350" height="196">
        </a>
        <nav class="main-nav" aria-label="<?= lang() === 'tr' ? 'Ana menü' : 'Main menu' ?>">
            <div class="nav-item has-sub">
                <a href="<?= e(url($pagesByKey['about']['slug_' . lang()] ?? '')) ?>"<?= $corporateActive ? ' class="active"' : '' ?>>
                    <?= e(t('nav.corporate')) ?>
                    <svg class="caret" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="m2.5 4.5 3.5 3.5 3.5-3.5"/></svg>
                </a>
                <div class="sub-menu">
                    <?php foreach ($corporateKeys as $k): if (!isset($pagesByKey[$k])) continue; $p = $pagesByKey[$k]; ?>
                    <a href="<?= e(url($p['slug_' . lang()])) ?>"<?= $activeKey === $k ? ' class="active"' : '' ?>><?= e($navLabel($p)) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php foreach ($topKeys as $k): if (!isset($pagesByKey[$k])) continue; $p = $pagesByKey[$k]; ?>
            <a href="<?= e(url($p['slug_' . lang()])) ?>"<?= $activeKey === $k ? ' class="active"' : '' ?>><?= e(lv($p, 'title')) ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="header-right">
            <div class="lang-switch" aria-label="Language">
                <a href="<?= e($alternates['tr'] ?? url('', 'tr')) ?>"<?= lang() === 'tr' ? ' class="active"' : '' ?>>TR</a>
                <a href="<?= e($alternates['en'] ?? url('', 'en')) ?>"<?= lang() === 'en' ? ' class="active"' : '' ?>>EN</a>
            </div>
            <button class="nav-toggle" aria-label="Menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<div class="mobile-nav">
    <a href="<?= e(url('')) ?>"><?= e(t('nav.home')) ?></a>
    <span class="mobile-group"><?= e(t('nav.corporate')) ?></span>
    <?php foreach ($corporateKeys as $k): if (!isset($pagesByKey[$k])) continue; $p = $pagesByKey[$k]; ?>
    <a class="sub<?= $activeKey === $k ? ' active' : '' ?>" href="<?= e(url($p['slug_' . lang()])) ?>"><?= e($navLabel($p)) ?></a>
    <?php endforeach; ?>
    <?php foreach ($topKeys as $k): if (!isset($pagesByKey[$k])) continue; $p = $pagesByKey[$k]; ?>
    <a href="<?= e(url($p['slug_' . lang()])) ?>"<?= $activeKey === $k ? ' class="active"' : '' ?>><?= e(lv($p, 'title')) ?></a>
    <?php endforeach; ?>
    <div class="mobile-lang">
        <a href="<?= e($alternates['tr'] ?? url('', 'tr')) ?>"<?= lang() === 'tr' ? ' class="active"' : '' ?>>Türkçe</a>
        <a href="<?= e($alternates['en'] ?? url('', 'en')) ?>"<?= lang() === 'en' ? ' class="active"' : '' ?>>English</a>
    </div>
</div>
<main>
