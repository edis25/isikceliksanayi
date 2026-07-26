<?php require __DIR__ . '/partials/header.php'; ?>

<section class="error-page">
    <div>
        <div class="code">404</div>
        <h1><?= e(t('notfound.title')) ?></h1>
        <p><?= e(t('notfound.text')) ?></p>
        <a class="btn" href="<?= e(url('')) ?>"><?= e(t('notfound.home')) ?></a>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
