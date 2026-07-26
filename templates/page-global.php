<?php
$intro   = $sections['intro'] ?? null;
$regions = array_filter([$sections['region-na'] ?? null, $sections['region-me'] ?? null, $sections['region-eu'] ?? null]);
$supply  = $sections['supply'] ?? null;
$trade   = $sections['trade'] ?? null;

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
                <p class="eyebrow"><?= e(t('nav.global')) ?></p>
                <h2><?= e(lv($intro, 'title')) ?></h2>
                <p class="split-sub"><?= e(lv($intro, 'subtitle')) ?></p>
                <?= nl2p(lv($intro, 'body')) ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($regions): ?>
<section class="section tight">
    <div class="container">
        <?php foreach ($regions as $i => $r): ?>
        <div class="region-block reveal">
            <h3><?= e(lv($r, 'title')) ?></h3>
            <?= nl2p(lv($r, 'body')) ?>
            <?php $items = section_items($r); if ($items): ?>
            <ul class="checks">
                <?php foreach ($items as $it): ?>
                <li><?= e(is_array($it) ? ($it['text'] ?? '') : $it) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($supply): ?>
<section class="section">
    <div class="container">
        <div class="split reverse">
            <div class="split-media reveal">
                <img src="<?= e(upload_url($supply['image'])) ?>" alt="<?= e(lv($supply, 'title')) ?>" loading="lazy">
            </div>
            <div class="split-body reveal reveal-d1">
                <p class="eyebrow"><?= e(t('nav.global')) ?></p>
                <h2><?= e(lv($supply, 'title')) ?></h2>
                <p class="split-sub"><?= e(lv($supply, 'subtitle')) ?></p>
                <?= nl2p(lv($supply, 'body')) ?>
                <?php $items = section_items($supply); if ($items): ?>
                <ul class="checks">
                    <?php foreach ($items as $it): ?>
                    <li><?= e(is_array($it) ? ($it['text'] ?? '') : $it) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($trade): ?>
<section class="dark-feature section">
    <?php if ($trade['image']): ?>
    <div class="bg" style="background-image:url('<?= e(upload_url($trade['image'])) ?>')"></div>
    <?php endif; ?>
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow"><?= e(t('nav.global')) ?></p>
            <h2 class="section-title" style="color:#fff"><?= e(lv($trade, 'title')) ?></h2>
        </div>
        <div class="reveal reveal-d1" style="max-width:640px">
            <?= nl2p(lv($trade, 'body')) ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
