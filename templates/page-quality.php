<?php
$intro = $sections['intro'] ?? null;
$certs = $sections['certs'] ?? null;

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
                <p class="eyebrow"><?= e(lv($page, 'title')) ?></p>
                <h2><?= e(lv($intro, 'title')) ?></h2>
                <p class="split-sub"><?= e(lv($intro, 'subtitle')) ?></p>
                <?= nl2p(lv($intro, 'body')) ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($certs && !empty($certs['data']['items'])): ?>
<section class="section tight" id="belgeler">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow"><?= e(lv($page, 'title')) ?></p>
            <h2 class="section-title"><?= e(lv($certs, 'title')) ?></h2>
        </div>
        <div class="cert-grid">
            <?php foreach ($certs['data']['items'] as $i => $c):
                $href = !empty($c['pdf']) ? asset($c['pdf']) : asset($c['image']);
                $sub = $c['sub_' . lang()] ?? $c['sub_tr'] ?? '';
            ?>
            <a class="cert-card reveal reveal-d<?= $i % 3 + 1 ?>" href="<?= e($href) ?>" target="_blank" rel="noopener">
                <div class="cert-img">
                    <img src="<?= e(asset($c['image'])) ?>" alt="<?= e($c['title'] . ' — ' . $sub) ?>" loading="lazy">
                </div>
                <h3><?= e($c['title']) ?></h3>
                <p><?= e($sub) ?></p>
                <span class="cert-link"><?= !empty($c['pdf']) ? 'PDF' : (lang() === 'tr' ? 'Görüntüle' : 'View') ?> <span class="arr">→</span></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
