<?php
session_boot();
$intro = $sections['intro'] ?? null;
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

<section class="section" id="contact-form">
    <div class="container">
        <div class="contact-layout">
            <div class="contact-info-card reveal">
                <h3><?= e(t('contact.info_title')) ?></h3>
                <div class="contact-item">
                    <div class="ico"><?= icon_svg('pin') ?></div>
                    <div>
                        <div class="lbl"><?= e(t('contact.address')) ?></div>
                        <div class="val"><?= e(setting('address')) ?></div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="ico"><?= icon_svg('phone') ?></div>
                    <div>
                        <div class="lbl"><?= e(t('contact.phone')) ?></div>
                        <a class="val" href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="ico"><?= icon_svg('mail') ?></div>
                    <div>
                        <div class="lbl"><?= e(t('contact.email')) ?></div>
                        <a class="val" href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a>
                    </div>
                </div>
            </div>
            <div class="reveal reveal-d1">
                <h2 style="margin-bottom:26px"><?= e(t('contact.form_title')) ?></h2>
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
                        <div class="full">
                            <button class="btn" type="submit"><?= e(t('btn.send')) ?> <span class="arr">→</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (($map = setting('map_embed')) !== ''): ?>
        <div class="map-embed reveal">
            <iframe src="<?= e($map) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?= e(setting('site_name')) ?>"></iframe>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
