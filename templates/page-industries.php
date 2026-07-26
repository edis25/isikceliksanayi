<?php
$intro = $sections['intro'] ?? null;
$allSectors = $db->all('SELECT * FROM sectors WHERE is_published = 1 ORDER BY sort_order');

require __DIR__ . '/partials/header.php';
$heroLead = $intro ? lv($intro, 'subtitle') : lv($page, 'meta_desc');
require __DIR__ . '/partials/page-hero.php';
?>

<section class="section">
    <div class="container">
        <?php if ($intro): ?>
        <div class="section-head center reveal">
            <p class="eyebrow"><?= e(t('nav.industries')) ?></p>
            <h2 class="section-title"><?= e(lv($intro, 'title')) ?></h2>
            <?php if (lv($intro, 'subtitle')): ?>
            <p class="section-lead"><?= e(lv($intro, 'subtitle')) ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="icon-grid">
            <?php foreach ($allSectors as $i => $s): ?>
            <div class="icon-card reveal reveal-d<?= $i % 3 + 1 ?>">
                <div class="ico"><?= icon_svg($s['icon'] ?: 'industry') ?></div>
                <h3><?= e(lv($s, 'name')) ?></h3>
                <p><?= e(lv($s, 'desc')) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
