<?php
$article = $ctx['article'];

$seo['title']       = lv($article, 'meta_title') ?: lv($article, 'title');
$seo['description'] = lv($article, 'meta_desc') ?: excerpt(lv($article, 'summary'));
$seo['image']       = upload_url($article['image']);
$seo['type']        = 'article';

$jsonld_extra = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'NewsArticle',
    'headline' => lv($article, 'title'),
    'description' => excerpt(lv($article, 'summary'), 300),
    'image'    => [upload_url($article['image'])],
    'datePublished' => $article['published_at'],
    'publisher' => ['@type' => 'Organization', 'name' => setting('site_name', 'Işık Çelik')],
    'mainEntityOfPage' => $seo['canonical'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

require __DIR__ . '/partials/header.php';

$heroTitle = lv($article, 'title');
$heroLead  = format_date($article['published_at']);
$heroImage = $article['image'];
$extraCrumb = ['label' => lv($page, 'title'), 'url' => url($page['slug_' . lang()])];
require __DIR__ . '/partials/page-hero.php';
?>

<section class="section">
    <div class="container">
        <article class="article-layout">
            <?php if ($article['image']): ?>
            <div class="article-cover reveal">
                <img src="<?= e(upload_url($article['image'])) ?>" alt="<?= e(lv($article, 'title')) ?>">
            </div>
            <?php endif; ?>
            <div class="article-body reveal reveal-d1">
                <?= nl2p(lv($article, 'body')) ?>
            </div>
            <a class="text-link" href="<?= e(url($page['slug_' . lang()])) ?>">← <?= e(t('btn.all_news')) ?></a>
        </article>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
