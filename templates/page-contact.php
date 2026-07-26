<?php
session_boot();
$intro     = $sections['intro'] ?? null;
$locations = $sections['locations'] ?? null;
$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

/* Ürün sayfasından "Teklif İste" ile gelindiyse konu alanını doldur */
$prefillSubject = '';
if (($p = trim($_GET['urun'] ?? '')) !== '') {
    $prefillSubject = (lang() === 'tr' ? 'Teklif Talebi: ' : 'Quote Request: ') . mb_substr($p, 0, 150);
}

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
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section tight" id="contact-form">
    <div class="container">
        <div class="contact-form-box reveal">
            <div class="section-head center">
                <p class="eyebrow"><?= e(t('nav.contact')) ?></p>
                <h2 class="section-title"><?= e(t('contact.form_title')) ?></h2>
            </div>
            <?php if ($flashSuccess): ?>
            <div class="alert alert-success"><?= e($flashSuccess) ?></div>
            <?php endif; ?>
            <?php if ($flashError): ?>
            <div class="alert alert-error"><?= e($flashError) ?></div>
            <?php endif; ?>
            <form method="post" action="<?= e(url($page['slug_' . lang()])) ?>">
                <?= csrf_field() ?>
                <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                <div class="form-grid">
                    <div class="field">
                        <label><?= e(t('form.name')) ?> <span class="req">*</span></label>
                        <input type="text" name="name" required maxlength="150">
                    </div>
                    <div class="field">
                        <label><?= e(t('form.email')) ?> <span class="req">*</span></label>
                        <input type="email" name="email" required maxlength="190">
                    </div>
                    <div class="field">
                        <label><?= e(t('form.phone')) ?></label>
                        <input type="tel" name="phone" maxlength="50">
                    </div>
                    <div class="field">
                        <label><?= e(t('form.subject')) ?></label>
                        <input type="text" name="subject" maxlength="190" value="<?= e($prefillSubject) ?>">
                    </div>
                    <div class="field full">
                        <label><?= e(t('form.message')) ?> <span class="req">*</span></label>
                        <textarea name="message" required maxlength="5000"></textarea>
                    </div>
                    <div class="full" style="text-align:center">
                        <button class="btn" type="submit"><?= e(t('btn.send')) ?> <span class="arr">→</span></button>
                    </div>
                </div>
            </form>
        </div>

        <?php if (($map = setting('map_embed')) !== ''): ?>
        <div class="map-embed reveal">
            <iframe src="<?= e($map) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?= e(setting('site_name')) ?>"></iframe>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
