<?php
$intro = $sections['intro'] ?? null;

$catSlugField = 'slug_' . lang();
$allCategories = $db->all('SELECT * FROM categories WHERE is_published = 1 ORDER BY sort_order, id');

/* Kategori başına ürün sayıları */
$counts = [];
foreach ($db->all('SELECT category_id, COUNT(*) AS c FROM products WHERE is_published = 1 GROUP BY category_id') as $r) {
    $counts[(int) $r['category_id']] = (int) $r['c'];
}
$totalCount = array_sum($counts);

/* Filtreler: ?cat=<slug> ve ?q=<arama> */
$activeCatSlug = trim($_GET['cat'] ?? '');
$query = trim($_GET['q'] ?? '');
$activeCat = null;
foreach ($allCategories as $c) {
    if ($activeCatSlug !== '' && ($c['slug_tr'] === $activeCatSlug || $c['slug_en'] === $activeCatSlug)) {
        $activeCat = $c;
        break;
    }
}

$sql = 'SELECT * FROM products WHERE is_published = 1';
$params = [];
if ($activeCat) {
    $sql .= ' AND category_id = :cid';
    $params['cid'] = $activeCat['id'];
}
if ($query !== '') {
    $sql .= ' AND (name_tr LIKE :q OR name_en LIKE :q OR summary_tr LIKE :q OR summary_en LIKE :q)';
    $params['q'] = '%' . $query . '%';
}
$sql .= ' ORDER BY sort_order';
$listProducts = $db->all($sql, $params);

$catsById = [];
foreach ($allCategories as $c) {
    $catsById[(int) $c['id']] = $c;
}

$baseListUrl = url($page['slug_' . lang()]);

require __DIR__ . '/partials/header.php';
$heroLead = $intro ? lv($intro, 'subtitle') : lv($page, 'meta_desc');
require __DIR__ . '/partials/page-hero.php';
?>

<section class="section">
    <div class="container">
        <div class="shop-layout">
            <aside class="shop-sidebar reveal">
                <form class="shop-search" method="get" action="<?= e($baseListUrl) ?>">
                    <?php if ($activeCat): ?>
                    <input type="hidden" name="cat" value="<?= e($activeCat[$catSlugField]) ?>">
                    <?php endif; ?>
                    <input type="search" name="q" value="<?= e($query) ?>" placeholder="<?= e(t('shop.search')) ?>" aria-label="<?= e(t('shop.search')) ?>">
                    <button type="submit" aria-label="<?= e(t('shop.search')) ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    </button>
                </form>

                <div class="shop-cats">
                    <h3><?= e(t('shop.categories')) ?></h3>
                    <ul>
                        <li>
                            <a href="<?= e($baseListUrl) ?>"<?= !$activeCat ? ' class="active"' : '' ?>>
                                <?= e(t('shop.all')) ?> <span class="cnt"><?= $totalCount ?></span>
                            </a>
                        </li>
                        <?php foreach ($allCategories as $c): ?>
                        <li>
                            <a href="<?= e($baseListUrl . '?cat=' . rawurlencode($c[$catSlugField])) ?>"<?= $activeCat && $activeCat['id'] === $c['id'] ? ' class="active"' : '' ?>>
                                <?= e(lv($c, 'name')) ?> <span class="cnt"><?= $counts[(int) $c['id']] ?? 0 ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="shop-help">
                    <h3><?= e(t('btn.quote')) ?></h3>
                    <p><?= lang() === 'tr' ? 'Tonaj ve ölçü bilgisiyle bize ulaşın, size özel teklif hazırlayalım.' : 'Contact us with tonnage and size details for a tailored offer.' ?></p>
                    <a class="btn" href="<?= e(url($pagesByKey['contact']['slug_' . lang()] ?? 'iletisim')) ?>"><?= e(t('btn.contact_us')) ?></a>
                </div>
            </aside>

            <div class="shop-main">
                <div class="shop-toolbar reveal">
                    <span class="shop-count">
                        <?php if ($activeCat): ?><strong><?= e(lv($activeCat, 'name')) ?></strong> · <?php endif; ?>
                        <?php if ($query !== ''): ?>"<?= e($query) ?>" · <?php endif; ?>
                        <?= count($listProducts) ?> <?= e(t('shop.count')) ?>
                    </span>
                    <?php if ($activeCat || $query !== ''): ?>
                    <a class="text-link" href="<?= e($baseListUrl) ?>"><?= e(t('shop.all')) ?> <span class="arr">→</span></a>
                    <?php endif; ?>
                </div>

                <?php if (!$listProducts): ?>
                <p class="shop-empty"><?= e(t('shop.noresult')) ?></p>
                <?php else: ?>
                <div class="cards-grid shop-grid" id="product-grid">
                    <?php foreach ($listProducts as $i => $pr): $prCat = $catsById[(int) $pr['category_id']] ?? null; ?>
                    <a class="card reveal reveal-d<?= $i % 3 + 1 ?>" href="<?= e(url($page['slug_' . lang()] . '/' . $pr['slug_' . lang()])) ?>" data-name="<?= e(mb_strtolower(lv($pr, 'name') . ' ' . lv($pr, 'summary'), 'UTF-8')) ?>">
                        <div class="card-media contain">
                            <img src="<?= e(upload_url($pr['image'])) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy">
                            <?php if ($hoverImg = product_hover_image($pr)): ?>
                            <img class="hover-img" src="<?= e(upload_url($hoverImg)) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy">
                            <?php endif; ?>
                            <?php if ($prCat): ?>
                            <span class="cat-badge"><?= e(lv($prCat, 'name')) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h3><?= e(lv($pr, 'name')) ?></h3>
                            <p><?= e(excerpt(lv($pr, 'summary'), 110)) ?></p>
                            <span class="card-cta"><?= e(t('btn.details')) ?> <span class="arr">→</span></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
