<?php
/**
 * İç sayfa başlık bandı.
 * Değişkenler: $heroTitle, $heroLead, $heroImage, $extraCrumb (['label' =>, 'url' =>] | null)
 */
$heroTitle = $heroTitle ?? lv($page, 'title');
$heroLead  = $heroLead ?? '';
$heroImage = $heroImage ?? ($page['image'] ?? '');
?>
<section class="page-hero">
    <?php if ($heroImage): ?>
    <div class="bg" style="background-image:url('<?= e(upload_url($heroImage)) ?>')"></div>
    <?php endif; ?>
    <div class="container">
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="<?= e(url('')) ?>"><?= e(t('breadcrumb.home')) ?></a>
            <span class="sep">/</span>
            <?php if (!empty($extraCrumb)): ?>
            <a href="<?= e($extraCrumb['url']) ?>"><?= e($extraCrumb['label']) ?></a>
            <span class="sep">/</span>
            <span><?= e($heroTitle) ?></span>
            <?php else: ?>
            <span><?= e($heroTitle) ?></span>
            <?php endif; ?>
        </nav>
        <h1><?= e($heroTitle) ?></h1>
        <?php if ($heroLead): ?>
        <p class="lead"><?= e($heroLead) ?></p>
        <?php endif; ?>
    </div>
</section>
