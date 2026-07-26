<?php
$transparentHeader = true;

$hero    = $sections['hero'] ?? null;
$stats   = $sections['stats'] ?? null;
$about   = $sections['about'] ?? null;
$prod    = $sections['production'] ?? null;
$prodIntro = $sections['products-intro'] ?? null;
$sust    = $sections['sustainability'] ?? null;
$global  = $sections['global'] ?? null;
$cta     = $sections['cta'] ?? null;

$productsPage = $pagesByKey['products'] ?? null;
$newsPage     = $pagesByKey['news'] ?? null;
$contactPage  = $pagesByKey['contact'] ?? null;

$homeProducts = $db->all('SELECT * FROM products WHERE is_published = 1 ORDER BY sort_order LIMIT 6');
$latestNews   = $db->all('SELECT * FROM news WHERE is_published = 1 ORDER BY published_at DESC LIMIT 3');

$linkFor = function (?string $pkey) use ($pagesByKey): string {
    if (!$pkey || !isset($pagesByKey[$pkey])) {
        return url('');
    }
    return url($pagesByKey[$pkey]['slug_' . lang()]);
};

require __DIR__ . '/partials/header.php';
?>

<?php if ($hero): $heroData = $hero['data']; ?>
<section class="hero">
    <div class="hero-media">
        <?php if (!empty($heroData['video']) && is_file(__DIR__ . '/../' . $heroData['video'])): ?>
        <video autoplay muted loop playsinline preload="metadata" poster="<?= e(asset($heroData['poster'] ?? 'assets/img/hero-poster.jpg')) ?>">
            <source src="<?= e(asset($heroData['video'])) ?>" type="video/mp4">
        </video>
        <?php else: ?>
        <img src="<?= e(upload_url($hero['image'] ?: 'assets/img/sicak-cekim.jpg')) ?>" alt="<?= e(setting('site_name')) ?>" fetchpriority="high">
        <?php endif; ?>
    </div>
    <div class="container">
        <p class="hero-eyebrow"><?= e(t('since')) ?> · Karabük</p>
        <h1><?= e(lv($hero, 'title')) ?></h1>
        <p class="hero-sub"><?= e(lv($hero, 'subtitle')) ?></p>
        <div class="hero-actions">
            <a class="btn" href="<?= e($linkFor('products')) ?>"><?= e(t('btn.all_products')) ?> <span class="arr">→</span></a>
            <a class="btn btn-ghost" href="<?= e($linkFor('about')) ?>"><?= e(t('btn.discover')) ?></a>
        </div>
    </div>
    <div class="scroll-hint"><?= e(t('scroll.down')) ?></div>
</section>
<?php endif; ?>

<?php if ($stats): ?>
<div class="stats-band">
    <div class="container">
        <div class="stats-grid">
            <?php foreach (section_items($stats) as $item): ?>
            <div class="stat">
                <div class="stat-value" data-value="<?= e($item['value']) ?>"><?= e($item['value']) ?></div>
                <div class="stat-label"><?= e($item['label']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($about): ?>
<section class="section">
    <div class="container">
        <div class="split">
            <div class="split-media reveal">
                <img src="<?= e(upload_url($about['image'])) ?>" alt="<?= e(lv($about, 'title')) ?>" loading="lazy">
            </div>
            <div class="split-body reveal reveal-d1">
                <p class="eyebrow"><?= e(t('nav.about')) ?></p>
                <h2><?= e(lv($about, 'title')) ?></h2>
                <p class="split-sub"><?= e(lv($about, 'subtitle')) ?></p>
                <?= nl2p(lv($about, 'body')) ?>
                <a class="text-link" href="<?= e($linkFor($about['data']['link_page'] ?? 'about')) ?>"><?= e(t('btn.discover')) ?> <span class="arr">→</span></a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($prod): ?>
<section class="section tight">
    <div class="container">
        <div class="split reverse">
            <div class="split-media reveal">
                <img src="<?= e(upload_url($prod['image'])) ?>" alt="<?= e(lv($prod, 'title')) ?>" loading="lazy">
            </div>
            <div class="split-body reveal reveal-d1">
                <p class="eyebrow"><?= e(t('nav.production')) ?></p>
                <h2><?= e(lv($prod, 'title')) ?></h2>
                <p class="split-sub"><?= e(lv($prod, 'subtitle')) ?></p>
                <?= nl2p(lv($prod, 'body')) ?>
                <a class="text-link" href="<?= e($linkFor($prod['data']['link_page'] ?? 'production')) ?>"><?= e(t('btn.discover')) ?> <span class="arr">→</span></a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($homeProducts && $productsPage): ?>
<section class="section">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow"><?= e(t('nav.products')) ?></p>
            <h2 class="section-title"><?= e($prodIntro ? lv($prodIntro, 'title') : t('products.title')) ?></h2>
            <?php if ($prodIntro && lv($prodIntro, 'subtitle')): ?>
            <p class="section-lead"><?= e(lv($prodIntro, 'subtitle')) ?></p>
            <?php endif; ?>
        </div>
        <div class="cards-grid">
            <?php foreach ($homeProducts as $i => $pr): ?>
            <a class="card reveal reveal-d<?= min($i % 3 + 1, 3) ?>" href="<?= e(url($productsPage['slug_' . lang()] . '/' . $pr['slug_' . lang()])) ?>">
                <div class="card-media contain">
                    <img src="<?= e(upload_url($pr['image'])) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy">
                    <?php if ($hoverImg = product_hover_image($pr)): ?>
                    <img class="hover-img" src="<?= e(upload_url($hoverImg)) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h3><?= e(lv($pr, 'name')) ?></h3>
                    <p><?= e(excerpt(lv($pr, 'summary'), 110)) ?></p>
                    <span class="card-cta"><?= e(t('btn.details')) ?> <span class="arr">→</span></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($sust): ?>
<section class="dark-feature section">
    <div class="bg" style="background-image:url('<?= e(upload_url($sust['image'])) ?>')"></div>
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow"><?= e(t('nav.sustainability')) ?></p>
            <h2 class="section-title on-dark" style="color:#fff"><?= e(lv($sust, 'title')) ?></h2>
            <p class="split-sub"><?= e(lv($sust, 'subtitle')) ?></p>
        </div>
        <div class="reveal reveal-d1" style="max-width:600px">
            <?= nl2p(lv($sust, 'body')) ?>
            <a class="btn" style="margin-top:26px" href="<?= e($linkFor($sust['data']['link_page'] ?? 'sustainability')) ?>"><?= e(t('btn.discover')) ?> <span class="arr">→</span></a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($global): ?>
<section class="section">
    <div class="container">
        <div class="split">
            <div class="split-media reveal">
                <img src="<?= e(upload_url($global['image'])) ?>" alt="<?= e(lv($global, 'title')) ?>" loading="lazy">
            </div>
            <div class="split-body reveal reveal-d1">
                <p class="eyebrow"><?= e(t('nav.global')) ?></p>
                <h2><?= e(lv($global, 'title')) ?></h2>
                <p class="split-sub"><?= e(lv($global, 'subtitle')) ?></p>
                <?= nl2p(lv($global, 'body')) ?>
                <a class="text-link" href="<?= e($linkFor($global['data']['link_page'] ?? 'global')) ?>"><?= e(t('btn.discover')) ?> <span class="arr">→</span></a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($latestNews && $newsPage): ?>
<section class="section tight">
    <div class="container">
        <div class="section-head reveal" style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:20px;max-width:none">
            <div>
                <p class="eyebrow"><?= e(t('nav.news')) ?></p>
                <h2 class="section-title"><?= e(t('news.latest')) ?></h2>
            </div>
            <a class="text-link" href="<?= e($linkFor('news')) ?>"><?= e(t('btn.all_news')) ?> <span class="arr">→</span></a>
        </div>
        <div class="news-grid">
            <?php foreach ($latestNews as $i => $n): ?>
            <a class="news-card reveal reveal-d<?= $i + 1 ?>" href="<?= e(url($newsPage['slug_' . lang()] . '/' . $n['slug_' . lang()])) ?>">
                <div class="card-media"><img src="<?= e(upload_url($n['image'])) ?>" alt="<?= e(lv($n, 'title')) ?>" loading="lazy"></div>
                <div class="card-body">
                    <span class="news-date"><?= e(format_date($n['published_at'])) ?></span>
                    <h3><?= e(lv($n, 'title')) ?></h3>
                    <p><?= e(excerpt(lv($n, 'summary'), 110)) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($cta): ?>
<section class="cta-band">
    <div class="bg" style="background-image:url('<?= e(upload_url($cta['image'])) ?>')"></div>
    <div class="container">
        <h2 class="reveal"><?= e(lv($cta, 'title')) ?></h2>
        <p class="reveal reveal-d1"><?= e(lv($cta, 'body')) ?></p>
        <a class="btn reveal reveal-d2" href="<?= e($linkFor($cta['data']['link_page'] ?? 'contact')) ?>"><?= e(t('btn.contact_us')) ?> <span class="arr">→</span></a>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
