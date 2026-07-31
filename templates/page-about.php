<?php
$intro    = $sections['intro'] ?? null;
$stats    = $sections['stats'] ?? null;
$timeline = $sections['timeline'] ?? null;
$vision   = $sections['vision'] ?? null;
$goals    = $sections['goals'] ?? null;

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

<?php if ($stats): ?>
<div class="stats-band">
    <div class="container">
        <?php if (lv($stats, 'title')): ?>
        <p class="eyebrow" style="margin-bottom:30px"><?= e(lv($stats, 'title')) ?></p>
        <?php endif; ?>
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

<?php if ($timeline && ($tlItems = section_items($timeline))): ?>
<section class="tlx">
    <div class="tlx-head">
        <div class="container">
            <p class="eyebrow reveal"><?= e(lv($timeline, 'title')) ?></p>
            <h2 class="reveal reveal-d1"><?= e(lv($timeline, 'subtitle')) ?></h2>
            <span class="tlx-hint reveal reveal-d2"><?= lang() === 'tr' ? 'Kaydırarak keşfedin' : 'Scroll to explore' ?> ↓</span>
        </div>
    </div>
    <?php foreach ($tlItems as $i => $item): ?>
    <article class="tlx-chapter">
        <?php if (!empty($item['image'])): ?>
        <div class="tlx-bg" style="background-image:url('<?= e(upload_url($item['image'])) ?>')"></div>
        <?php endif; ?>
        <div class="container tlx-content">
            <div class="tlx-year reveal"><?= e($item['year'] ?? '') ?></div>
            <div class="tlx-text">
                <h3 class="reveal reveal-d1"><?= e($item['title'] ?? '') ?></h3>
                <p class="reveal reveal-d2"><?= e($item['text'] ?? '') ?></p>
            </div>
        </div>
    </article>
    <?php endforeach; ?>
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
