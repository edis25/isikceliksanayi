<?php
$intro    = $sections['intro'] ?? null;
$ges      = $sections['ges'] ?? null;
$benefits = $sections['benefits'] ?? null;

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
                <p class="eyebrow"><?= e(t('nav.sustainability')) ?></p>
                <h2><?= e(lv($intro, 'title')) ?></h2>
                <p class="split-sub"><?= e(lv($intro, 'subtitle')) ?></p>
                <?= nl2p(lv($intro, 'body')) ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($ges): ?>
<div class="stats-band">
    <div class="container">
        <p class="eyebrow" style="margin-bottom:30px"><?= e(lv($ges, 'title')) ?></p>
        <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr))">
            <?php foreach (section_items($ges) as $item): ?>
            <div class="stat">
                <div class="stat-value" data-value="<?= e($item['value']) ?>"><?= e($item['value']) ?></div>
                <div class="stat-label"><?= e($item['label']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($benefits): ?>
<section class="section">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow"><?= e(t('nav.sustainability')) ?></p>
            <h2 class="section-title"><?= e(lv($benefits, 'title')) ?></h2>
        </div>
        <div class="icon-grid">
            <?php foreach (section_items($benefits) as $i => $item): ?>
            <div class="icon-card reveal reveal-d<?= $i + 1 ?>">
                <div class="ico"><?= icon_svg($item['icon'] ?? 'leaf') ?></div>
                <h3><?= e($item['title']) ?></h3>
                <p><?= e($item['text']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
