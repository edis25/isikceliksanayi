<?php
$intro  = $sections['intro'] ?? null;
$vision = $sections['vision'] ?? null;
$goals  = $sections['goals'] ?? null;

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
                <p class="eyebrow"><?= e(t('nav.about')) ?></p>
                <h2><?= e(lv($intro, 'title')) ?></h2>
                <?= nl2p(lv($intro, 'body')) ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vision): ?>
<section class="dark-feature section">
    <?php if ($vision['image']): ?>
    <div class="bg" style="background-image:url('<?= e(upload_url($vision['image'])) ?>')"></div>
    <?php endif; ?>
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow"><?= e(lv($vision, 'title')) ?></p>
            <h2 class="section-title" style="color:#fff"><?= e(lv($vision, 'subtitle')) ?></h2>
        </div>
        <div class="icon-grid on-dark">
            <?php foreach (section_items($vision) as $i => $item): ?>
            <div class="icon-card reveal reveal-d<?= $i + 1 ?>">
                <h3 style="color:var(--c-accent-2)"><?= e($item['title']) ?></h3>
                <p><?= e($item['text']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($goals): ?>
<section class="section">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow"><?= e(t('nav.about')) ?></p>
            <h2 class="section-title"><?= e(lv($goals, 'title')) ?></h2>
        </div>
        <div class="icon-grid">
            <?php foreach (section_items($goals) as $i => $item): ?>
            <div class="icon-card reveal reveal-d<?= $i + 1 ?>">
                <h3 style="color:var(--c-accent)"><?= e($item['title']) ?></h3>
                <p><?= e($item['text']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
