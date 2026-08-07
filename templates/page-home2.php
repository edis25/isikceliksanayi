<?php
/**
 * Kreatif ana sayfa — "Ateşten Dünyaya" scroll yolculuğu.
 * GSAP ScrollTrigger ile; JS yüklenmezse tüm içerik statik olarak görünür.
 */
$transparentHeader = true;

$hero    = $sections['hero'] ?? null;
$stats   = $sections['stats'] ?? null;
$about   = $sections['about'] ?? null;
$sust    = $sections['sustainability'] ?? null;
$global  = $sections['global'] ?? null;
$cta     = $sections['cta'] ?? null;

$productsPage = $pagesByKey['products'] ?? null;
$newsPage     = $pagesByKey['news'] ?? null;
$contactPage  = $pagesByKey['contact'] ?? null;

$allProducts = $db->all('SELECT * FROM products WHERE is_published = 1 ORDER BY sort_order');
$latestNews  = $db->all('SELECT * FROM news WHERE is_published = 1 ORDER BY published_at DESC LIMIT 3');

$linkFor = function (?string $pkey) use ($pagesByKey): string {
    if (!$pkey || !isset($pagesByKey[$pkey])) {
        return url('');
    }
    return url($pagesByKey[$pkey]['slug_' . lang()]);
};

$tr = lang() === 'tr';

/* Üretim yolculuğu sahneleri (S2) */
$stages = [
    [
        'no'    => '01',
        'title' => $tr ? 'KÜTÜK' : 'BILLET',
        'text'  => $tr ? 'Hibrit tedarik modeliyle kesintisiz ve kaliteli hammadde akışı.' : 'Uninterrupted, high-quality raw material flow through our hybrid sourcing model.',
        'video' => 'assets/video/scene-kutuk.mp4',
    ],
    [
        'no'    => '02',
        'title' => $tr ? 'ROBOTİK HAT' : 'ROBOTIC LINE',
        'text'  => $tr ? 'Dijital üretim altyapısı ve tam otomasyonla insansız paketleme.' : 'Automated handling with digital manufacturing infrastructure.',
        'video' => 'assets/video/scene-robot.mp4',
    ],
    [
        'no'    => '03',
        'title' => $tr ? 'KAPASİTE' : 'CAPACITY',
        'text'  => $tr ? 'Yeni tesisle üretim gücümüz üç katına çıktı.' : 'Our new facility tripled our production capacity.',
        'video' => 'assets/video/scene-kapasite.mp4',
        'big'   => '450.000',
        'bigLabel' => $tr ? 'TON / YIL' : 'TONS / YEAR',
    ],
];

require __DIR__ . '/partials/header.php';
?>
<link rel="stylesheet" href="<?= e(asset('assets/css/home2.css')) ?>">

<!-- S1 — AÇILIŞ -->
<section class="c-hero" id="s-hero">
    <div class="c-hero-media">
        <video autoplay muted loop playsinline preload="auto" poster="<?= e(asset('assets/img/hero-poster.jpg')) ?>">
            <source src="<?= e(asset('assets/video/hero.mp4')) ?>" type="video/mp4">
        </video>
    </div>
    <div class="container c-hero-content">
        <p class="c-eyebrow"><?= e(t('since')) ?></p>
        <h1 class="c-title" data-split><?= e($hero ? lv($hero, 'title') : 'Daha Parlak Bir Gelecek İçin') ?></h1>
        <p class="c-sub"><?= e($hero ? lv($hero, 'subtitle') : '') ?></p>
        <div class="c-actions">
            <a class="btn" href="<?= e($linkFor('products')) ?>"><?= e(t('btn.all_products')) ?> <span class="arr">→</span></a>
            <a class="btn btn-ghost" href="<?= e($linkFor('about')) ?>"><?= e(t('btn.discover')) ?></a>
        </div>
    </div>
    <div class="c-marquee" aria-hidden="true">
        <span>IŞIK ÇELİK — 1965 — STEEL — 450.000 TON — IŞIK ÇELİK — 1965 — STEEL — 450.000 TON — </span>
    </div>
</section>

<!-- S1b — SAYILAR -->
<?php if ($stats): ?>
<section class="c-stats" id="s-stats">
    <div class="container">
        <div class="c-stats-grid">
            <?php foreach (section_items($stats) as $item): ?>
            <div class="c-stat">
                <div class="stat-value" data-value="<?= e($item['value']) ?>"><?= e($item['value']) ?></div>
                <div class="c-stat-label"><?= e($item['label']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- S2 — ÜRETİM YOLCULUĞU (pinned) -->
<section class="c-journey" id="s-journey">
    <div class="c-journey-viewport">
        <div class="c-journey-media">
            <?php foreach ($stages as $i => $st): ?>
            <video class="c-stage-video<?= $i === 0 ? ' active' : '' ?>" muted loop playsinline preload="auto" data-stage="<?= $i ?>">
                <source src="<?= e(asset($st['video'])) ?>" type="video/mp4">
            </video>
            <?php endforeach; ?>
            <div class="c-journey-shade"></div>
        </div>
        <div class="container c-journey-content">
            <div class="c-journey-rail">
                <div class="c-journey-progress"></div>
            </div>
            <div class="c-journey-texts">
                <p class="c-eyebrow"><?= $tr ? 'ÜRETİM YOLCULUĞU' : 'PRODUCTION JOURNEY' ?></p>
                <?php foreach ($stages as $i => $st): ?>
                <div class="c-stage<?= $i === 0 ? ' active' : '' ?>" data-stage="<?= $i ?>">
                    <span class="c-stage-no"><?= e($st['no']) ?></span>
                    <h2><?= e($st['title']) ?></h2>
                    <?php if (!empty($st['big'])): ?>
                    <div class="c-stage-big"><span class="stat-value" data-value="<?= e($st['big']) ?>"><?= e($st['big']) ?></span> <small><?= e($st['bigLabel']) ?></small></div>
                    <?php endif; ?>
                    <p><?= e($st['text']) ?></p>
                </div>
                <?php endforeach; ?>
                <div class="c-stage-dots">
                    <?php foreach ($stages as $i => $st): ?>
                    <span class="dot<?= $i === 0 ? ' active' : '' ?>" data-stage="<?= $i ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- S3 — ÜRÜN VİTRİNİ (yatay) -->
<?php if ($allProducts && $productsPage): ?>
<section class="c-shelf" id="s-shelf">
    <div class="c-shelf-viewport">
        <div class="container c-shelf-head">
            <p class="c-eyebrow"><?= e(t('nav.products')) ?></p>
            <h2 class="c-shelf-title" data-split><?= $tr ? 'Çelik, her formda.' : 'Steel, in every form.' ?></h2>
            <span class="c-shelf-hint"><?= $tr ? 'Kaydırmaya devam edin' : 'Keep scrolling' ?> →</span>
        </div>
        <div class="c-track">
            <?php foreach ($allProducts as $i => $pr): ?>
            <a class="c-product" href="<?= e(url($productsPage['slug_' . lang()] . '/' . $pr['slug_' . lang()])) ?>">
                <span class="c-product-no"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                <div class="c-product-img">
                    <img src="<?= e(upload_url($pr['image'])) ?>" alt="<?= e(lv($pr, 'name')) ?>" loading="lazy">
                    <?php if ($hoverImg = product_hover_image($pr)): ?>
                    <img class="hover-img" src="<?= e(upload_url($hoverImg)) ?>" alt="" loading="lazy">
                    <?php endif; ?>
                </div>
                <h3><?= e(lv($pr, 'name')) ?></h3>
                <span class="c-product-cta"><?= e(t('btn.details')) ?> →</span>
            </a>
            <?php endforeach; ?>
            <a class="c-product c-product-all" href="<?= e($linkFor('products')) ?>">
                <span class="c-product-all-num"><?= count($allProducts) ?></span>
                <h3><?= e(t('btn.all_products')) ?> →</h3>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- S4 — ENERJİ -->
<?php if ($sust): ?>
<section class="c-energy" id="s-energy">
    <div class="c-energy-media">
        <video muted loop playsinline autoplay preload="metadata">
            <source src="<?= e(asset('assets/video/scene-ges.mp4')) ?>" type="video/mp4">
        </video>
    </div>
    <div class="container c-energy-content">
        <p class="c-eyebrow"><?= e(t('nav.sustainability')) ?></p>
        <h2 data-split><?= e(lv($sust, 'title')) ?></h2>
        <div class="c-energy-num">
            <span class="stat-value" data-value="6.803">6.803</span>
            <small>kWe</small>
        </div>
        <p class="c-energy-text"><?= e(excerpt(lv($sust, 'body'), 220)) ?></p>
        <a class="text-link" href="<?= e($linkFor('sustainability')) ?>" style="color:var(--c-accent-2)"><?= e(t('btn.discover')) ?> <span class="arr">→</span></a>
    </div>
</section>
<?php endif; ?>

<!-- S5 — GLOBAL -->
<section class="c-global" id="s-global">
    <div class="container">
        <p class="c-eyebrow"><?= e(t('nav.global')) ?></p>
        <h2 class="c-global-title" data-split><?= $tr ? "Anadolu'dan 5 kıtaya" : 'From Anatolia to 5 continents' ?></h2>
        <div class="c-global-viz">
            <svg viewBox="0 0 1000 340" preserveAspectRatio="none" aria-hidden="true">
                <circle class="c-origin" cx="80" cy="290" r="7"/>
                <circle class="c-origin-pulse" cx="80" cy="290" r="7"/>
                <path class="c-arc" d="M80 290 Q 260 40 480 90"/>
                <path class="c-arc" d="M80 290 Q 350 120 640 170"/>
                <path class="c-arc" d="M80 290 Q 380 240 700 265"/>
                <path class="c-arc" d="M80 290 Q 500 60 900 110"/>
                <circle class="c-dest" cx="480" cy="90" r="5"/>
                <circle class="c-dest" cx="640" cy="170" r="5"/>
                <circle class="c-dest" cx="700" cy="265" r="5"/>
                <circle class="c-dest" cx="900" cy="110" r="5"/>
            </svg>
            <span class="c-region" style="left:44%;top:16%"><?= $tr ? 'Avrupa' : 'Europe' ?></span>
            <span class="c-region" style="left:62%;top:44%"><?= $tr ? 'Orta Doğu' : 'Middle East' ?></span>
            <span class="c-region" style="left:67%;top:74%"><?= $tr ? 'Kuzey Afrika' : 'North Africa' ?></span>
            <span class="c-region" style="left:87%;top:23%"><?= $tr ? 'Latin Amerika' : 'Latin America' ?></span>
            <span class="c-region c-region-origin" style="left:8%;top:88%"><?= $tr ? 'ANADOLU' : 'ANATOLIA' ?></span>
        </div>
        <div class="c-global-foot">
            <p><?= e($global ? excerpt(lv($global, 'body'), 180) : '') ?></p>
            <a class="text-link" href="<?= e($linkFor('global')) ?>"><?= e(t('btn.discover')) ?> <span class="arr">→</span></a>
        </div>
    </div>
    <div class="c-marquee c-marquee-global" aria-hidden="true">
        <span>5 <?= $tr ? 'KITA' : 'CONTINENTS' ?> — 100+ <?= $tr ? 'ÜLKE' : 'COUNTRIES' ?> — <?= $tr ? 'İHRACAT' : 'EXPORT' ?> — 5 <?= $tr ? 'KITA' : 'CONTINENTS' ?> — 100+ <?= $tr ? 'ÜLKE' : 'COUNTRIES' ?> — <?= $tr ? 'İHRACAT' : 'EXPORT' ?> — </span>
    </div>
</section>

<!-- S6 — HABERLER + CTA -->
<?php if ($latestNews && $newsPage): ?>
<section class="c-news" id="s-news">
    <div class="container">
        <div class="c-news-head">
            <div>
                <p class="c-eyebrow"><?= e(t('nav.news')) ?></p>
                <h2><?= e(t('news.latest')) ?></h2>
            </div>
            <a class="text-link" href="<?= e($linkFor('news')) ?>"><?= e(t('btn.all_news')) ?> <span class="arr">→</span></a>
        </div>
        <div class="news-grid">
            <?php foreach ($latestNews as $i => $n): ?>
            <a class="news-card c-news-card" href="<?= e(url($newsPage['slug_' . lang()] . '/' . $n['slug_' . lang()])) ?>">
                <div class="card-media"><img src="<?= e(upload_url($n['image'])) ?>" alt="<?= e(lv($n, 'title')) ?>" loading="lazy"></div>
                <div class="card-body">
                    <span class="news-date"><?= e(format_date($n['published_at'])) ?></span>
                    <h3><?= e(lv($n, 'title')) ?></h3>
                    <p><?= e(excerpt(lv($n, 'summary'), 100)) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($cta): ?>
<section class="c-cta" id="s-cta">
    <div class="c-cta-bg" style="background-image:url('<?= e(upload_url($cta['image'])) ?>')"></div>
    <div class="container">
        <h2 data-split><?= e(lv($cta, 'title')) ?></h2>
        <p><?= e(lv($cta, 'body')) ?></p>
        <a class="btn c-magnetic" href="<?= e($linkFor($cta['data']['link_page'] ?? 'contact')) ?>"><?= e(t('btn.contact_us')) ?> <span class="arr">→</span></a>
    </div>
</section>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>
<script src="<?= e(asset('assets/js/home2.js')) ?>" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
