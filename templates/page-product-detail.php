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

$productCat = $product['category_id']
    ? $db->row('SELECT * FROM categories WHERE id = :id AND is_published = 1', ['id' => $product['category_id']])
    : null;

/* İlgili ürünler: önce aynı kategoriden */
$others = $db->all(
    'SELECT * FROM products WHERE is_published = 1 AND id != :id ORDER BY (category_id = :cid) DESC, sort_order LIMIT 3',
    ['id' => $product['id'], 'cid' => (int) $product['category_id']]
);
$contactPage = $pagesByKey['contact'] ?? null;

/* Teklif İste → WhatsApp (numara panelden yönetilir); numara yoksa iletişim sayfasına düşer */
$waNumber = preg_replace('/\D/', '', setting('whatsapp'));
$waText = (lang() === 'tr'
    ? 'Merhaba, ' . lv($product, 'name') . ' ürünü hakkında teklif almak istiyorum. '
    : 'Hello, I would like to request a quote for ' . lv($product, 'name') . '. ')
    . ($alternates[lang()] ?? '');
$quoteUrl = $waNumber !== ''
    ? 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waText)
    : ($contactPage ? url($contactPage['slug_' . lang()]) : url(''));
$quoteIsWa = $waNumber !== '';

/* Galeri: ana görsel + ek fotoğraflar */
$galleryImages = [];
if (!empty($product['image'])) {
    $galleryImages[] = $product['image'];
}
if (!empty($product['gallery'])) {
    foreach (json_decode($product['gallery'], true) ?: [] as $g) {
        if ($g) {
            $galleryImages[] = $g;
        }
    }
}

/* Öznitelikler: Üretim Standartı / Kalite / Boy ... (eski siteden birebir) */
$attributes = [];
if (!empty($product['attributes'])) {
    $attributes = json_decode($product['attributes'], true) ?: [];
}

require __DIR__ . '/partials/header.php';
?>

<div class="crumb-band">
    <div class="container">
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="<?= e(url('')) ?>"><?= e(t('breadcrumb.home')) ?></a>
            <span class="sep">/</span>
            <a href="<?= e(url($page['slug_' . lang()])) ?>"><?= e(lv($page, 'title')) ?></a>
            <?php if ($productCat): ?>
            <span class="sep">/</span>
            <a href="<?= e(url($page['slug_' . lang()]) . '?cat=' . rawurlencode($productCat['slug_' . lang()])) ?>"><?= e(lv($productCat, 'name')) ?></a>
            <?php endif; ?>
            <span class="sep">/</span>
            <span><?= e(lv($product, 'name')) ?></span>
        </nav>
    </div>
</div>

<section class="product-detail">
    <div class="container">
        <div class="product-panel reveal">
            <div class="product-panel-media">
                <div class="product-figure">
                    <img id="product-main-img" src="<?= e(upload_url($galleryImages[0] ?? '')) ?>" alt="<?= e(lv($product, 'name')) ?>">
                </div>
                <?php if (count($galleryImages) > 1): ?>
                <div class="product-thumbs">
                    <?php foreach ($galleryImages as $i => $g): ?>
                    <button type="button" class="product-thumb<?= $i === 0 ? ' active' : '' ?>" data-src="<?= e(upload_url($g)) ?>" aria-label="<?= e(lv($product, 'name')) ?> <?= $i + 1 ?>">
                        <img src="<?= e(upload_url($g)) ?>" alt="" loading="lazy">
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="product-panel-info">
                <?php if ($productCat): ?>
                <a class="eyebrow" href="<?= e(url($page['slug_' . lang()]) . '?cat=' . rawurlencode($productCat['slug_' . lang()])) ?>"><?= e(lv($productCat, 'name')) ?></a>
                <?php endif; ?>
                <h1><?= e(lv($product, 'name')) ?></h1>
                <?php if (lv($product, 'summary')): ?>
                <p class="product-summary"><?= e(lv($product, 'summary')) ?></p>
                <?php endif; ?>

                <?php if ($attributes): ?>
                <div class="attr-list">
                    <?php foreach ($attributes as $attr): ?>
                    <div class="attr-row">
                        <span class="attr-label"><?= e($attr['label_' . lang()] ?? $attr['label_tr'] ?? '') ?></span>
                        <span class="attr-values">
                            <?php foreach (($attr['values'] ?? []) as $v): ?>
                            <span class="chip"><?= e($v) ?></span>
                            <?php endforeach; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?= nl2p(lv($product, 'body')) ?>

                <div class="detail-actions">
                    <a class="btn<?= $quoteIsWa ? ' btn-wa' : '' ?>" href="<?= e($quoteUrl) ?>"<?= $quoteIsWa ? ' target="_blank" rel="noopener"' : '' ?>>
                        <?php if ($quoteIsWa): ?><span class="btn-ico"><?= icon_svg('whatsapp') ?></span><?php endif; ?>
                        <?= e(t('btn.quote')) ?> <span class="arr">→</span>
                    </a>
                    <a class="btn btn-dark" href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a>
                </div>
            </div>
        </div>

        <?php if (!empty($product['spec_table'])): ?>
        <div class="spec-card reveal">
            <div class="spec-card-head">
                <h2><?= lang() === 'tr' ? 'Üretim Ölçüleri' : 'Production Range' ?></h2>
                <span class="hint"><?= lang() === 'tr' ? 'mm | inç' : 'mm | inch' ?></span>
            </div>
            <div class="spec-table-wrap">
                <?= $product['spec_table'] /* eski siteden birebir aktarılan tablo; admin panelden düzenlenebilir */ ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($others): ?>
        <div class="related-block">
        <h2 class="spec-title reveal"><?= e(t('products.related')) ?></h2>
        <div class="cards-grid">
            <?php foreach ($others as $i => $pr): ?>
            <a class="card reveal reveal-d<?= $i + 1 ?>" href="<?= e(url($page['slug_' . lang()] . '/' . $pr['slug_' . lang()])) ?>">
                <div class="card-media contain">
                    <img src="<?= e(upload_url($pr['image'])) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy">
                    <?php if ($hoverImg = product_hover_image($pr)): ?>
                    <img class="hover-img" src="<?= e(upload_url($hoverImg)) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h3><?= e(lv($pr, 'name')) ?></h3>
                    <p><?= e(excerpt(lv($pr, 'summary'), 100)) ?></p>
                    <span class="card-cta"><?= e(t('btn.details')) ?> <span class="arr">→</span></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
