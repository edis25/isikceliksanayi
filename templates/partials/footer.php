</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand" href="<?= e(url('')) ?>">
                    <?= brand_mark() ?>
                    <span class="brand-text">IŞIK ÇELİK<small><?= lang() === 'tr' ? 'ÇELİK SANAYİ' : 'STEEL INDUSTRY' ?></small></span>
                </a>
                <p><?= e(t('footer.slogan')) ?></p>
            </div>
            <div class="footer-col">
                <h4><?= e(t('footer.quicklinks')) ?></h4>
                <ul>
                    <?php foreach (['about', 'production', 'sustainability', 'industries', 'global', 'news'] as $k): if (!isset($pagesByKey[$k])) continue; $p = $pagesByKey[$k]; ?>
                    <li><a href="<?= e(url($p['slug_' . lang()])) ?>"><?= e(lv($p, 'title')) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?= e(t('footer.products')) ?></h4>
                <ul>
                    <?php
                    $footerProducts = Database::get()->all('SELECT * FROM products WHERE is_published = 1 ORDER BY sort_order LIMIT 6');
                    $productsPage = $pagesByKey['products'] ?? null;
                    foreach ($footerProducts as $fp): if (!$productsPage) break; ?>
                    <li><a href="<?= e(url($productsPage['slug_' . lang()] . '/' . $fp['slug_' . lang()])) ?>"><?= e(lv($fp, 'name')) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?= e(t('footer.contact')) ?></h4>
                <ul class="footer-contact">
                    <li><?= icon_svg('pin') ?><span><?= e(setting('address')) ?></span></li>
                    <li><?= icon_svg('phone') ?><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></li>
                    <li><?= icon_svg('mail') ?><a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <span>© <?= date('Y') ?> <?= e(setting('site_name', 'Işık Çelik')) ?>. <?= e(t('footer.rights')) ?></span>
            <span><?= lang() === 'tr' ? "1965'ten beri Karabük'te" : 'In Karabük since 1965' ?></span>
        </div>
    </div>
</footer>
<script src="<?= e(asset('assets/js/main.js')) ?>" defer></script>
</body>
</html>
