<?php
$intro     = $sections['intro'] ?? null;
$locations = $sections['locations'] ?? null;

require __DIR__ . '/partials/header.php';
$heroLead = $intro ? lv($intro, 'subtitle') : '';
require __DIR__ . '/partials/page-hero.php';
?>

<?php if ($locations && ($locItems = section_items($locations))): ?>
<section class="section">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow"><?= e(t('nav.contact')) ?></p>
            <h2 class="section-title"><?= e(lv($locations, 'title')) ?></h2>
        </div>
        <div class="locations-grid">
            <?php foreach ($locItems as $i => $loc): ?>
            <?php
            // Harita bağlantısı: özel 'map' alanı varsa o, yoksa adresten Google Maps araması
            $mapUrl = !empty($loc['map'])
                ? $loc['map']
                : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(str_replace("\n", ' ', ($loc['title'] ?? '') . ' ' . ($loc['address'] ?? '')));
            ?>
            <div class="loc-card reveal reveal-d<?= $i % 3 + 1 ?>">
                <h3><?= e($loc['title'] ?? '') ?></h3>
                <p class="loc-address"><?= nl2br(e($loc['address'] ?? '')) ?></p>
                <ul class="loc-rows">
                    <?php foreach (($loc['phones'] ?? []) as $phone): ?>
                    <li><span class="lbl">T</span><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phone)) ?>"><?= e($phone) ?></a></li>
                    <?php endforeach; ?>
                    <?php if (!empty($loc['fax'])): ?>
                    <li><span class="lbl">F</span><span><?= e($loc['fax']) ?></span></li>
                    <?php endif; ?>
                    <?php foreach (($loc['emails'] ?? []) as $mail): ?>
                    <li><span class="lbl">E</span><a href="mailto:<?= e($mail) ?>"><?= e($mail) ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <a class="loc-map-link" href="<?= e($mapUrl) ?>" target="_blank" rel="noopener">
                    <?= icon_svg('pin') ?> <?= e(t('contact.map')) ?> <span class="arr">→</span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (($map = setting('map_embed')) !== ''): ?>
<section class="section tight">
    <div class="container">
        <div class="map-embed reveal">
            <iframe src="<?= e($map) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?= e(setting('site_name')) ?>"></iframe>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
