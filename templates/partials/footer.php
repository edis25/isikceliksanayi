</main>
<footer class="site-footer">
    <?php
    // Ulusal liste başarıları — footer rozet şeridi
    $awards = lang() === 'tr' ? [
        ['num' => '500', 'org' => 'FORTUNE 500', 'sub' => 'Türkiye — En Büyük 500 Şirket'],
        ['num' => '500', 'org' => 'İSO 500', 'sub' => "Türkiye'nin 500 Büyük Sanayi Kuruluşu"],
        ['num' => '100', 'org' => 'TİM 100', 'sub' => 'Türkiye’nin İlk 100 İhracatçısı'],
    ] : [
        ['num' => '500', 'org' => 'FORTUNE 500', 'sub' => "Türkiye's 500 Largest Companies"],
        ['num' => '500', 'org' => 'ISO 500', 'sub' => "Türkiye's Top 500 Industrial Enterprises"],
        ['num' => '100', 'org' => 'TİM 100', 'sub' => "Türkiye's Top 100 Exporters"],
    ];
    ?>
    <div class="awards-band">
        <div class="container">
            <p class="awards-title"><?= lang() === 'tr' ? 'ULUSAL SIRALAMALARDA IŞIK ÇELİK' : 'IŞIK ÇELİK IN NATIONAL RANKINGS' ?></p>
            <div class="awards-grid">
                <?php foreach ($awards as $a): ?>
                <div class="award">
                    <span class="award-seal" aria-hidden="true">
                        <svg viewBox="0 0 72 72" fill="none">
                            <circle cx="36" cy="36" r="34" stroke="currentColor" stroke-width="1.4" stroke-dasharray="2.5 3.5"/>
                            <circle cx="36" cy="36" r="28" stroke="currentColor" stroke-width="1.6"/>
                            <path d="M20 49l-4 5M52 49l4 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                            <text x="36" y="41" text-anchor="middle" font-family="Archivo, sans-serif" font-size="17" font-weight="800" fill="currentColor"><?= e($a['num']) ?></text>
                        </svg>
                    </span>
                    <div class="award-text">
                        <strong><?= e($a['org']) ?></strong>
                        <span><?= e($a['sub']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand" href="<?= e(url('')) ?>">
                    <img class="brand-logo" src="<?= e(asset('assets/img/logo-light.png')) ?>" alt="<?= e(setting('site_name', 'Işık Çelik')) ?>" width="350" height="196">
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
