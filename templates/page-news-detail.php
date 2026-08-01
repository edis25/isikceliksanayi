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

            <?php if (!empty($article['attachment'])): ?>
            <div class="doc-card reveal">
                <div class="doc-card-info">
                    <span class="doc-ico">PDF</span>
                    <div>
                        <strong><?= lang() === 'tr' ? 'Ekli Belge' : 'Attached Document' ?></strong>
                        <span class="doc-name"><?= e(basename($article['attachment'])) ?></span>
                    </div>
                </div>
                <a class="btn" href="<?= e(upload_url($article['attachment'])) ?>" target="_blank" rel="noopener">
                    <?= lang() === 'tr' ? 'Belgeyi Görüntüle' : 'View Document' ?> <span class="arr">→</span>
                </a>
            </div>
            <div class="pdf-embed reveal">
                <iframe src="<?= e(upload_url($article['attachment'])) ?>#view=FitH" title="<?= e(lv($article, 'title')) ?>" loading="lazy"></iframe>
            </div>
            <?php endif; ?>

            <a class="text-link" href="<?= e(url($page['slug_' . lang()])) ?>">← <?= e(t('btn.all_news')) ?></a>
        </article>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
