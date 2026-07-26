<?php
$product = $ctx['product'];

$seo['title']       = lv($product, 'meta_title') ?: lv($product, 'name');
$seo['description'] = lv($product, 'meta_desc') ?: excerpt(lv($product, 'summary'));
$seo['image']       = upload_url($product['image']);
$seo['type']        = 'product';

$jsonld_extra = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Product',
    'name'     => lv($product, 'name'),
    'description' => excerpt(lv($product, 'summary'), 300),
    'image'    => upload_url($product['image']),
    'brand'    => ['@type' => 'Brand', 'name' => setting('site_name', 'Işık Çelik')],
    'url'      => $seo['canonical'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$others = $db->all('SELECT * FROM products WHERE is_published = 1 AND id != :id ORDER BY sort_order LIMIT 3', ['id' => $product['id']]);
$contactPage = $pagesByKey['contact'] ?? null;

require __DIR__ . '/partials/header.php';

$heroTitle = lv($product, 'name');
$heroLead  = lv($product, 'summary');
$heroImage = $page['image'];
$extraCrumb = ['label' => lv($page, 'title'), 'url' => url($page['slug_' . lang()])];
require __DIR__ . '/partials/page-hero.php';
?>

<section class="section">
    <div class="container">
        <div class="split">
            <div class="split-media reveal">
                <div class="product-figure">
                    <img src="<?= e(upload_url($product['image'])) ?>" alt="<?= e(lv($product, 'name')) ?>">
                </div>
            </div>
            <div class="split-body reveal reveal-d1">
                <p class="eyebrow"><?= e(t('nav.products')) ?></p>
                <h2><?= e(lv($product, 'name')) ?></h2>
                <?php if (lv($product, 'summary')): ?>
                <p class="split-sub"><?= e(lv($product, 'summary')) ?></p>
                <?php endif; ?>
                <?= nl2p(lv($product, 'body')) ?>
                <?php if ($contactPage): ?>
                <a class="btn" href="<?= e(url($contactPage['slug_' . lang()])) ?>"><?= e(t('btn.contact_us')) ?> <span class="arr">→</span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($product['spec_table'])): ?>
<section class="section tight">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow"><?= e(t('nav.products')) ?></p>
            <h2 class="section-title"><?= lang() === 'tr' ? 'Üretim Ölçüleri' : 'Production Range' ?></h2>
        </div>
        <div class="spec-table-wrap reveal">
            <?= $product['spec_table'] /* eski siteden birebir aktarılan tablo; admin panelden düzenlenebilir */ ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($others): ?>
<section class="section tight">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow"><?= e(t('nav.products')) ?></p>
            <h2 class="section-title"><?= e(t('products.related')) ?></h2>
        </div>
        <div class="cards-grid">
            <?php foreach ($others as $i => $pr): ?>
            <a class="card reveal reveal-d<?= $i + 1 ?>" href="<?= e(url($page['slug_' . lang()] . '/' . $pr['slug_' . lang()])) ?>">
                <div class="card-media contain"><img src="<?= e(upload_url($pr['image'])) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy"></div>
                <div class="card-body">
                    <h3><?= e(lv($pr, 'name')) ?></h3>
                    <p><?= e(excerpt(lv($pr, 'summary'), 100)) ?></p>
                    <span class="card-cta"><?= e(t('btn.details')) ?> <span class="arr">→</span></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
