<?php
$intro    = $sections['intro'] ?? null;
$features = $sections['features'] ?? null;
$gallery  = $sections['gallery'] ?? null;

require __DIR__ . '/partials/header.php';
$heroLead = $intro ? lv($intro, 'subtitle') : '';
require __DIR__ . '/partials/page-hero.php';
?>

<?php if ($intro): ?>
<section class="section">
    <div class="container">
        <div class="split">
            <div class="split-media reveal">
                <img src="<?= e(upload_url($intro['image'])) ?>" alt="<?= e(lv($intro, 'title')) ?>" loading="lazy">
            </div>
            <div class="split-body reveal reveal-d1">
                <p class="eyebrow"><?= e(t('nav.production')) ?></p>
                <h2><?= e(lv($intro, 'title')) ?></h2>
                <p class="split-sub"><?= e(lv($intro, 'subtitle')) ?></p>
                <?= nl2p(lv($intro, 'body')) ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($features): ?>
<section class="section tight">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow"><?= e(t('nav.production')) ?></p>
            <h2 class="section-title"><?= e(lv($features, 'title')) ?></h2>
        </div>
        <div class="icon-grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr))">
            <?php foreach (section_items($features) as $i => $item): ?>
            <div class="icon-card reveal reveal-d<?= min($i + 1, 4) ?>">
                <div class="ico"><?= icon_svg($item['icon'] ?? 'industry') ?></div>
                <h3><?= e($item['title']) ?></h3>
                <p><?= e($item['text']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($gallery && !empty($gallery['data']['images'])): ?>
<section class="section">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow"><?= e(t('nav.production')) ?></p>
            <h2 class="section-title"><?= e(lv($gallery, 'title')) ?></h2>
        </div>
        <div class="gallery-grid">
            <?php foreach ($gallery['data']['images'] as $i => $img): ?>
            <figure class="reveal reveal-d<?= $i % 2 + 1 ?>">
                <img src="<?= e(upload_url($img['src'])) ?>" alt="<?= e($img['alt_' . lang()] ?? $img['alt_tr'] ?? '') ?>" loading="lazy">
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
