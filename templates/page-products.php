<?php
$intro = $sections['intro'] ?? null;
$allProducts = $db->all('SELECT * FROM products WHERE is_published = 1 ORDER BY sort_order');

require __DIR__ . '/partials/header.php';
$heroLead = $intro ? lv($intro, 'subtitle') : lv($page, 'meta_desc');
require __DIR__ . '/partials/page-hero.php';
?>

<section class="section">
    <div class="container">
        <?php if ($intro && lv($intro, 'title')): ?>
        <div class="section-head reveal">
            <p class="eyebrow"><?= e(t('nav.products')) ?></p>
            <h2 class="section-title"><?= e(lv($intro, 'title')) ?></h2>
        </div>
        <?php endif; ?>
        <div class="cards-grid">
            <?php foreach ($allProducts as $i => $pr): ?>
            <a class="card reveal reveal-d<?= $i % 3 + 1 ?>" href="<?= e(url($page['slug_' . lang()] . '/' . $pr['slug_' . lang()])) ?>">
                <div class="card-media"><img src="<?= e(upload_url($pr['image'])) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy"></div>
                <div class="card-body">
                    <h3><?= e(lv($pr, 'name')) ?></h3>
                    <p><?= e(excerpt(lv($pr, 'summary'), 120)) ?></p>
                    <span class="card-cta"><?= e(t('btn.details')) ?> <span class="arr">→</span></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
