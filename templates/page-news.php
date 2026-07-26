<?php
$perPage = 9;
$pageNo  = max(1, (int) ($_GET['p'] ?? 1));
$total   = (int) $db->value('SELECT COUNT(*) FROM news WHERE is_published = 1');
$totalPages = max(1, (int) ceil($total / $perPage));
$pageNo  = min($pageNo, $totalPages);
$offset  = ($pageNo - 1) * $perPage;
$items   = $db->all("SELECT * FROM news WHERE is_published = 1 ORDER BY published_at DESC LIMIT $perPage OFFSET $offset");

require __DIR__ . '/partials/header.php';
$heroLead = lv($page, 'meta_desc');
require __DIR__ . '/partials/page-hero.php';
?>

<section class="section">
    <div class="container">
        <?php if (!$items): ?>
        <p style="text-align:center"><?= e(t('news.empty')) ?></p>
        <?php else: ?>
        <div class="news-grid">
            <?php foreach ($items as $i => $n): ?>
            <a class="news-card reveal reveal-d<?= $i % 3 + 1 ?>" href="<?= e(url($page['slug_' . lang()] . '/' . $n['slug_' . lang()])) ?>">
                <div class="card-media"><img src="<?= e(upload_url($n['image'])) ?>" alt="<?= e(lv($n, 'title')) ?>" loading="lazy"></div>
                <div class="card-body">
                    <span class="news-date"><?= e(format_date($n['published_at'])) ?></span>
                    <h3><?= e(lv($n, 'title')) ?></h3>
                    <p><?= e(excerpt(lv($n, 'summary'), 120)) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php if ($totalPages > 1): ?>
        <div style="display:flex;gap:10px;justify-content:center;margin-top:50px">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a class="btn <?= $p === $pageNo ? '' : 'btn-dark' ?>" style="padding:10px 18px" href="<?= e(url($page['slug_' . lang()])) ?>?p=<?= $p ?>"><?= $p ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
