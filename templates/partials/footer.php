</main>
<footer class="site-footer">
    <?php
    // Ulusal liste başarıları — resmi rozetlerle footer şeridi
    $awards = lang() === 'tr' ? [
        ['logo' => 'assets/img/awards/fortune500.png', 'alt' => 'Fortune 500', 'org' => 'FORTUNE 500', 'sub' => 'Türkiye — En Büyük 500 Şirket'],
        ['logo' => 'assets/img/awards/iso500.png', 'alt' => 'İSO İkinci 500', 'org' => 'İSO İKİNCİ 500', 'sub' => "Türkiye'nin İkinci 500 Büyük Sanayi Kuruluşu"],
        ['logo' => 'assets/img/awards/tim-ilk1000.png', 'alt' => 'TİM İlk 1000', 'org' => 'TİM İLK 1000', 'sub' => 'Türkiye’nin İlk 1000 İhracatçı Firması'],
    ] : [
        ['logo' => 'assets/img/awards/fortune500.png', 'alt' => 'Fortune 500', 'org' => 'FORTUNE 500', 'sub' => "Türkiye's 500 Largest Companies"],
        ['logo' => 'assets/img/awards/iso500.png', 'alt' => 'ISO Second 500', 'org' => 'İSO SECOND 500', 'sub' => "Türkiye's Second Top 500 Industrial Enterprises"],
        ['logo' => 'assets/img/awards/tim-ilk1000.png', 'alt' => 'TİM Top 1000', 'org' => 'TİM TOP 1000', 'sub' => 'Top 1000 Exporters of Türkiye'],
    ];
    ?>
    <div class="awards-band">
        <div class="container">
            <p class="awards-title"><?= lang() === 'tr' ? 'ULUSAL SIRALAMALARDA IŞIK ÇELİK' : 'IŞIK ÇELİK IN NATIONAL RANKINGS' ?></p>
            <div class="awards-grid">
                <?php foreach ($awards as $a): ?>
                <div class="award">
                    <span class="award-logo">
                        <img src="<?= e(asset($a['logo'])) ?>" alt="<?= e($a['alt']) ?>" loading="lazy">
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
                <?php
                // İletişim sayfasındaki lokasyonlardan Merkez + Fabrika bilgileri
                $locRow = Database::get()->row(
                    "SELECT s.data_json FROM sections s JOIN pages p ON p.id = s.page_id WHERE p.pkey = 'contact' AND s.skey = 'locations' AND s.is_published = 1"
                );
                $locData = $locRow ? (json_decode($locRow['data_json'], true) ?: []) : [];
                $locItems = $locData['items_' . lang()] ?? $locData['items_tr'] ?? [];
                $footLocs = array_slice($locItems, 0, 2); // Karabük Merkez + Karabük Fabrika
                ?>
                <ul class="footer-contact">
                    <li><?= icon_svg('pin') ?><span><?= e(setting('address')) ?></span></li>
                    <?php foreach ($footLocs as $fl): if (empty($fl['phones'][0])) continue; ?>
                    <li>
                        <?= icon_svg('phone') ?>
                        <span>
                            <em class="fc-label"><?= e($fl['title'] ?? '') ?></em>
                            <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $fl['phones'][0])) ?>"><?= e($fl['phones'][0]) ?></a>
                        </span>
                    </li>
                    <?php endforeach; ?>
                    <?php if (!$footLocs): ?>
                    <li><?= icon_svg('phone') ?><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></li>
                    <?php endif; ?>
                    <li><?= icon_svg('mail') ?><a href="mailto:<?= e(setting('email2', 'info@isikcelik.com')) ?>"><?= e(setting('email2', 'info@isikcelik.com')) ?></a></li>
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
