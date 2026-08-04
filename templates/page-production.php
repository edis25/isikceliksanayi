<?php
$intro    = $sections['intro'] ?? null;
$video    = $sections['video'] ?? null;
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

<?php if ($video && !empty($video['data']['video'])): ?>
<section class="video-feature">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow" style="color:var(--c-accent-2)"><?= e(lv($video, 'title')) ?></p>
            <h2 class="section-title" style="color:#fff"><?= e(lv($video, 'subtitle')) ?></h2>
        </div>
        <div class="video-frame reveal reveal-d1" data-video-frame>
            <video preload="metadata" poster="<?= e(asset($video['data']['poster'] ?? '')) ?>" playsinline>
                <source src="<?= e(asset($video['data']['video'])) ?>" type="video/mp4">
            </video>
            <button type="button" class="video-play" aria-label="<?= lang() === 'tr' ? 'Videoyu oynat' : 'Play video' ?>">
                <span class="video-play-ring"></span>
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5-11-6.5Z"/></svg>
            </button>
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
