<?php
/**
 * Işık Çelik — başlangıç içeriği (TR/EN).
 * Kaynak: ISIK_CELIK_WEB_ICERIK_VE_GORSELLER içerik dokümanları.
 */

function seed_database(Database $db): void
{
    $now = date('Y-m-d H:i:s');

    /* ================= AYARLAR ================= */
    $settings = [
        ['site_name', 'Site Adı', 'Işık Çelik', 'Işık Çelik'],
        ['slogan', 'Slogan', 'Güçlü Altyapı. Sürdürülebilir Üretim. Küresel Vizyon.', 'Strong Infrastructure. Sustainable Manufacturing. Global Vision.'],
        ['phone', 'Telefon', '+90 (370) 424 20 77', '+90 (370) 424 20 77'],
        ['whatsapp', 'WhatsApp Numarası (ülke koduyla, örn: 905321234567)', '903704242077', '903704242077'],
        ['email', 'E-posta', 'export@isikcelik.com', 'export@isikcelik.com'],
        ['address', 'Adres', 'Karabük Organize Sanayi Bölgesi, Karabük / Türkiye', 'Karabük Organized Industrial Zone, Karabük, Türkiye'],
        ['map_embed', 'Google Maps Embed URL', '', ''],
        ['linkedin', 'LinkedIn', '', ''],
        ['instagram', 'Instagram', '', ''],
        ['youtube', 'YouTube', '', ''],
        ['ga_code', 'Google Analytics Kodu', '', ''],
        ['default_meta_title', 'Varsayılan Meta Başlık', 'Işık Çelik | Karabük Çelik Üreticisi', 'Işık Çelik | Steel Manufacturer in Türkiye'],
        ['default_meta_desc', 'Varsayılan Meta Açıklama', "1965'ten bu yana Karabük'te çelik üretimi. Yıllık 450.000 ton kapasite ile 5 kıtada 100'den fazla ülkeye ihracat.", "Steel manufacturing in Karabük since 1965. Exporting to 100+ countries across 5 continents with 450,000 tons annual capacity."],
    ];
    foreach ($settings as $s) {
        $db->insert('settings', ['skey' => $s[0], 'label' => $s[1], 'value_tr' => $s[2], 'value_en' => $s[3]]);
    }

    /* ================= SAYFALAR ================= */
    $pages = [
        'home' => [
            'template' => 'home', 'slug_tr' => '', 'slug_en' => '', 'sort_order' => 0,
            'title_tr' => 'Ana Sayfa', 'title_en' => 'Home',
            'meta_title_tr' => 'Işık Çelik | Çelik Üretiminde Yarım Asrı Aşan Güç',
            'meta_title_en' => 'Işık Çelik | Over Half a Century of Steel Manufacturing Excellence',
            'meta_desc_tr'  => "1965'ten bu yana Karabük'te çelik üretimi. Yıllık 450.000 ton kapasite, 5 kıtada 100'den fazla ülkeye ihracat. Sıcak ve soğuk çekilmiş çelik, profil, işlenmiş çubuk.",
            'meta_desc_en'  => "Steel manufacturing in Karabük, Türkiye since 1965. 450,000 tons annual capacity, exports to 100+ countries across 5 continents. Hot-drawn and cold-drawn steel, profiles, machined bars.",
            'image' => 'assets/img/tesis-havadan.jpg',
        ],
        'about' => [
            'template' => 'about', 'slug_tr' => 'kurumsal', 'slug_en' => 'about-us', 'sort_order' => 1,
            'title_tr' => 'Kurumsal', 'title_en' => 'About Us',
            'meta_title_tr' => 'Kurumsal | Işık Çelik — 1965\'ten Beri Güvenin Temsilcisi',
            'meta_title_en' => 'About Us | Işık Çelik — Trusted Steel Manufacturer Since 1965',
            'meta_desc_tr'  => "Işık Çelik, yarım asrı aşan tecrübesiyle çelik sektöründe güvenin ve istikrarın temsilcisidir. Uluslararası kalite standartlarında üretim, 100'den fazla ülkeye ihracat.",
            'meta_desc_en'  => "With more than half a century of experience, Işık Çelik represents reliability and excellence in the steel industry. International quality standards, exports to 100+ countries.",
            'image' => 'assets/img/tesis-vinc.jpg',
        ],
        'production' => [
            'template' => 'production', 'slug_tr' => 'uretim-teknoloji', 'slug_en' => 'production-technology', 'sort_order' => 2,
            'title_tr' => 'Üretim & Teknoloji', 'title_en' => 'Production & Technology',
            'meta_title_tr' => 'Üretim & Teknoloji | Işık Çelik — Yeni Nesil Üretim Tesisi',
            'meta_title_en' => 'Production & Technology | Işık Çelik — Next-Generation Facility',
            'meta_desc_tr'  => "Gelişmiş otomasyon, dijital üretim altyapısı ve yüksek hassasiyetli proses kontrolü. Yeni tesisimizle üretim kapasitemiz üç katına çıktı: yıllık 450.000 ton.",
            'meta_desc_en'  => "Advanced automation, digital manufacturing infrastructure and high-precision process control. Our new facility tripled our production capacity to 450,000 tons per year.",
            'image' => 'assets/img/uretim-robotik.jpg',
        ],
        'sustainability' => [
            'template' => 'sustainability', 'slug_tr' => 'surdurulebilirlik', 'slug_en' => 'sustainability', 'sort_order' => 3,
            'title_tr' => 'Sürdürülebilirlik & Enerji', 'title_en' => 'Sustainability & Energy',
            'meta_title_tr' => 'Sürdürülebilirlik & Enerji | Işık Çelik — 6.803 kWe GES',
            'meta_title_en' => 'Sustainability & Energy | Işık Çelik — 6.803 MWp Solar Power',
            'meta_desc_tr'  => "Toplam 6.803 kWe kurulu güce sahip çatı GES yatırımlarımızla karbon ayak izimizi azaltıyor, çevre dostu ve sürdürülebilir çelik üretimini önceliklendiriyoruz.",
            'meta_desc_en'  => "With 6.803 MWp of rooftop solar power capacity, we reduce our carbon footprint and prioritize environmentally responsible, sustainable steel manufacturing.",
            'image' => 'assets/img/ges-cati.jpg',
        ],
        'products' => [
            'template' => 'products', 'slug_tr' => 'urunler', 'slug_en' => 'products', 'sort_order' => 4,
            'title_tr' => 'Ürünlerimiz', 'title_en' => 'Our Products',
            'meta_title_tr' => 'Ürünlerimiz | Işık Çelik — Sıcak & Soğuk Çekilmiş Çelik, Profil',
            'meta_title_en' => 'Our Products | Işık Çelik — Hot & Cold-Drawn Steel, Profiles',
            'meta_desc_tr'  => "Sıcak çekilmiş ve soğuk çekilmiş çelik ürünler, çelik profiller, özel profiller, işlenmiş çubuklar ve bağlantı elemanları. Katma değerli üretimle global rekabet gücü.",
            'meta_desc_en'  => "Hot-drawn and cold-drawn steel products, steel profiles, custom profiles, machined bars and fasteners. Global competitiveness through value-added manufacturing.",
            'image' => 'assets/img/hadde-hatti.jpg',
        ],
        'industries' => [
            'template' => 'industries', 'slug_tr' => 'sektorler', 'slug_en' => 'industries', 'sort_order' => 5,
            'title_tr' => 'Sektörler', 'title_en' => 'Industries',
            'meta_title_tr' => 'Hizmet Verdiğimiz Sektörler | Işık Çelik',
            'meta_title_en' => 'Industries We Serve | Işık Çelik',
            'meta_desc_tr'  => "İnşaat, otomotiv, makine, tarım ekipmanları, mobilya sanayi ve sanayi yatırımları. Çelik ürünlerimiz altı ana sektörde yaygın olarak kullanılmaktadır.",
            'meta_desc_en'  => "Construction, automotive, machinery, agricultural equipment, furniture manufacturing and industrial investments. Our steel products serve six key industries.",
            'image' => 'assets/img/sicak-cekim.jpg',
        ],
        'global' => [
            'template' => 'global', 'slug_tr' => 'global', 'slug_en' => 'global-presence', 'sort_order' => 6,
            'title_tr' => 'Global Gücümüz', 'title_en' => 'Global Presence',
            'meta_title_tr' => 'Global Gücümüz | Işık Çelik — 5 Kıtada 100+ Ülkeye İhracat',
            'meta_title_en' => 'Global Presence | Işık Çelik — Exports to 100+ Countries',
            'meta_desc_tr'  => "Avrupa, Orta Doğu, Kuzey Afrika ve Latin Amerika başta olmak üzere 5 kıtada 100'den fazla ülkeye çelik ihracatı. Türkiye'nin global çelik gücü.",
            'meta_desc_en'  => "Steel exports to more than 100 countries across 5 continents, with strong positions in Europe, the Middle East, North Africa and Latin America.",
            'image' => 'assets/img/kizgin-cubuk.jpg',
        ],
        'news' => [
            'template' => 'news', 'slug_tr' => 'haberler', 'slug_en' => 'news', 'sort_order' => 7,
            'title_tr' => 'Haberler', 'title_en' => 'News',
            'meta_title_tr' => 'Haberler | Işık Çelik',
            'meta_title_en' => 'News | Işık Çelik',
            'meta_desc_tr'  => "Işık Çelik'ten güncel haberler: yatırımlar, üretim, sürdürülebilirlik ve sektör gelişmeleri.",
            'meta_desc_en'  => "Latest news from Işık Çelik: investments, production, sustainability and industry developments.",
            'image' => 'assets/img/tesis-havadan.jpg',
        ],
        'contact' => [
            'template' => 'contact', 'slug_tr' => 'iletisim', 'slug_en' => 'contact', 'sort_order' => 8,
            'title_tr' => 'İletişim', 'title_en' => 'Contact',
            'meta_title_tr' => 'İletişim | Işık Çelik — Karabük',
            'meta_title_en' => 'Contact | Işık Çelik — Karabük, Türkiye',
            'meta_desc_tr'  => "Işık Çelik ile iletişime geçin. Karabük Organize Sanayi Bölgesi. Tel: +90 (370) 424 20 77 — export@isikcelik.com",
            'meta_desc_en'  => "Get in touch with Işık Çelik. Karabük Organized Industrial Zone, Türkiye. Tel: +90 (370) 424 20 77 — export@isikcelik.com",
            'image' => 'assets/img/tesis-vinc.jpg',
        ],
    ];

    $pageIds = [];
    foreach ($pages as $pkey => $p) {
        $p['pkey'] = $pkey;
        $p['is_published'] = 1;
        $pageIds[$pkey] = $db->insert('pages', $p);
    }

    /* ================= BÖLÜMLER ================= */
    $sections = [];

    /* ---- ANA SAYFA ---- */
    $sections[] = ['home', 'hero', 'hero', 0,
        'Daha Parlak Bir Gelecek İçin', 'Building a Brighter Future',
        "1965'ten bu yana Karabük'te, yıllık 450.000 ton üretim kapasitesiyle Türkiye'nin global çelik gücünü temsil ediyoruz.",
        "Operating from Karabük since 1965, we represent Türkiye's global steel strength with an annual production capacity of 450,000 tons.",
        '', '',
        ['video' => 'assets/video/hero.mp4', 'poster' => 'assets/img/hero-poster.jpg'],
        'assets/img/sicak-cekim.jpg'];

    $sections[] = ['home', 'stats', 'stats', 1,
        'Rakamlarla Işık Çelik', 'Işık Çelik in Numbers', '', '', '', '',
        [
            'items_tr' => [
                ['value' => '1965', 'label' => 'Kuruluş Yılı'],
                ['value' => '450.000', 'label' => 'Ton / Yıl Üretim Kapasitesi'],
                ['value' => '100+', 'label' => 'Ülkeye İhracat'],
                ['value' => '5', 'label' => 'Kıtada Varlık'],
                ['value' => '6.803', 'label' => 'kWe Yenilenebilir Enerji'],
            ],
            'items_en' => [
                ['value' => '1965', 'label' => 'Founded'],
                ['value' => '450.000', 'label' => 'Tons / Year Capacity'],
                ['value' => '100+', 'label' => 'Countries Exported To'],
                ['value' => '5', 'label' => 'Continents'],
                ['value' => '6.803', 'label' => 'kWe Renewable Energy'],
            ],
        ], ''];

    $sections[] = ['home', 'about', 'split', 2,
        'Yarım Asrı Aşan Tecrübe', 'More Than Half a Century of Experience',
        'Türk çelik sektörünün köklü ve güvenilir üreticisi', "Türkiye's well-established and trusted steel manufacturer",
        "1965 yılından bu yana Karabük'te faaliyet gösteren Işık Çelik, kurulduğu günden itibaren kalite, sürdürülebilirlik ve müşteri memnuniyetini odağına almıştır.\n\nYeni üretim tesisinin devreye girmesiyle üretim kapasitesini üç katına çıkaran firmamız; modern teknolojik altyapısı, dijital üretim sistemleri ve çevre dostu enerji yatırımlarıyla sektöründe fark yaratmaya devam ediyor.",
        "Since 1965, Işık Çelik has remained committed to quality, sustainability and customer satisfaction from its base in Karabük.\n\nWith the commissioning of our new production facility, we have tripled our production capacity. Supported by advanced manufacturing technologies, digital production systems and environmentally responsible energy investments, we continue to lead the way in the steel industry.",
        ['link_page' => 'about'], 'assets/img/tesis-vinc.jpg'];

    $sections[] = ['home', 'production', 'split-reverse', 3,
        'Yeni Nesil Üretim Tesisi', 'Next-Generation Manufacturing Facility',
        'Dijital dönüşüm ve operasyonel mükemmellik', 'Digital transformation and operational excellence',
        "Yeni tesis yatırımımız yalnızca kapasite artışı değil; aynı zamanda dijital dönüşüm ve operasyonel verimlilik anlamına gelmektedir. Gelişmiş otomasyon sistemleri ve yüksek hassasiyetli proses kontrolü ile müşterilerimize sürdürülebilir ve rekabetçi çözümler sunuyoruz.",
        "Our new production facility represents more than an increase in capacity — it is a major step forward in digital transformation and operational excellence. With advanced automation systems and high-precision process control, we deliver sustainable and competitive solutions to our customers.",
        ['link_page' => 'production'], 'assets/img/uretim-robotik.jpg'];

    $sections[] = ['home', 'products-intro', 'lead', 4,
        'Ürün Gruplarımız', 'Our Product Groups',
        'Geniş ürün portföyümüz ile birçok sektöre çözüm sunuyoruz.', 'Our extensive product portfolio serves a wide range of industries.',
        '', '', [], ''];

    $sections[] = ['home', 'sustainability', 'dark-feature', 5,
        'Sürdürülebilirlik & Enerji', 'Sustainability & Energy',
        'Toplam 6.803 kWe yenilenebilir enerji', 'A total of 6.803 MWp renewable energy',
        "Çevreye duyarlı üretim anlayışı, şirket stratejimizin temel taşlarından biridir. Çatı GES yatırımlarımızla enerji maliyetlerimizi optimize ederken karbon ayak izimizi azaltıyor ve sürdürülebilir üretimi önceliklendiriyoruz.",
        "Environmental responsibility is one of the cornerstones of our corporate strategy. With our rooftop solar investments, we optimize energy costs while reducing our carbon footprint and prioritizing sustainable manufacturing.",
        ['link_page' => 'sustainability'], 'assets/img/ges-cati.jpg'];

    $sections[] = ['home', 'global', 'global', 6,
        'Global Gücümüz', 'Our Global Presence',
        '5 kıtada 100\'den fazla ülkeye ihracat', 'Exporting to 100+ countries across 5 continents',
        "İhracat, büyüme stratejimizin temel unsurlarındandır. Avrupa, Orta Doğu, Kuzey Afrika ve Latin Amerika başta olmak üzere dünya genelinde güçlü bir konuma sahibiz.",
        "Exports are one of the key pillars of our growth strategy. We hold strong market positions in Europe, the Middle East, North Africa and Latin America.",
        ['link_page' => 'global'], 'assets/img/kizgin-cubuk.jpg'];

    $sections[] = ['home', 'cta', 'cta', 7,
        'Projeniz için doğru çelik çözümü', 'The right steel solution for your project',
        '', '',
        "İhtiyacınıza uygun ürün ve teklif için ekibimizle iletişime geçin.",
        "Contact our team for the right product and a tailored offer.",
        ['link_page' => 'contact'], 'assets/img/hadde-hatti.jpg'];

    /* ---- KURUMSAL ---- */
    $sections[] = ['about', 'intro', 'split', 0,
        'Güvenin ve İstikrarın Temsilcisi', 'Reliability, Consistency and Excellence',
        'Yarım asrı aşan tecrübe', 'More than half a century of experience',
        "Işık Çelik, yarım asrı aşan tecrübesiyle çelik sektöründe güvenin ve istikrarın temsilcisidir.\n\nÜretim süreçlerimiz; uluslararası kalite standartlarına uygun, sürdürülebilir ve verimlilik odaklı bir anlayışla yürütülmektedir. Teknolojik altyapımız ve otomasyon yatırımlarımız sayesinde yüksek kalite standardını sürekli ve güvenilir biçimde sunuyoruz.\n\nBugün 5 kıtada 100'ün üzerinde ülkeye ihracat gerçekleştirerek Türkiye'nin global çelik gücünü temsil ediyoruz.",
        "With more than half a century of experience, Işık Çelik represents reliability, consistency and excellence in the steel industry.\n\nOur manufacturing processes are carried out in accordance with international quality standards, focusing on sustainability, operational efficiency and continuous improvement. Through our advanced technological infrastructure and automation investments, we consistently deliver high-quality products with dependable performance.\n\nToday, we proudly export to more than 100 countries across five continents, representing the strength of the Turkish steel industry in global markets.",
        [], 'assets/img/tesis-havadan.jpg'];

    $sections[] = ['about', 'vision', 'feature-list', 1,
        'Gelecek Vizyonu', 'Our Vision',
        '2026; büyüme, yenilenme ve dönüşüm yılıdır.', 'The year 2026 represents a new era of growth, transformation and innovation.',
        '', '',
        [
            'items_tr' => [
                ['title' => 'Kapasite', 'text' => 'Üretim kapasitemiz üç katına çıktı.'],
                ['title' => 'Yenilenebilir Enerji', 'text' => 'Yenilenebilir enerji yatırımlarımız tamamlandı.'],
                ['title' => 'Dijitalleşme', 'text' => 'Dijitalleşme ve otomasyon yatırımlarımız hızlandı.'],
            ],
            'items_en' => [
                ['title' => 'Capacity', 'text' => 'Our production capacity has tripled.'],
                ['title' => 'Renewable Energy', 'text' => 'Our renewable energy investments have been completed.'],
                ['title' => 'Digitalization', 'text' => 'Our digitalization and automation initiatives continue to accelerate.'],
            ],
        ], 'assets/img/sicak-cekim.jpg'];

    $sections[] = ['about', 'goals', 'feature-list', 2,
        'Yıl Sonu Hedeflerimiz', 'Our Year-End Objectives',
        '', '', '', '',
        [
            'items_tr' => [
                ['title' => '%20', 'text' => 'İhracat hacmimizi %20 artırmak'],
                ['title' => 'Büyüme', 'text' => 'Avrupa ve Orta Doğu pazarlarında daha agresif büyümek'],
                ['title' => 'Verimlilik', 'text' => 'Üretim verimliliğini en üst seviyeye taşımak'],
            ],
            'items_en' => [
                ['title' => '20%', 'text' => 'Increase export volume by 20%'],
                ['title' => 'Growth', 'text' => 'Accelerate growth across European and Middle Eastern markets'],
                ['title' => 'Efficiency', 'text' => 'Maximize manufacturing efficiency through continuous operational improvement'],
            ],
        ], ''];

    /* ---- ÜRETİM & TEKNOLOJİ ---- */
    $sections[] = ['production', 'intro', 'split', 0,
        'Yeni Nesil Üretim Tesisimiz', 'Next-Generation Manufacturing Facility',
        'Kapasite artışının ötesinde: dijital dönüşüm', 'Beyond capacity: digital transformation',
        "Yeni tesis yatırımımız yalnızca kapasite artışı değil; aynı zamanda dijital dönüşüm ve operasyonel verimlilik anlamına gelmektedir.\n\nBu yatırımlar sayesinde hem maliyet optimizasyonu sağlıyor hem de müşterilerimize sürdürülebilir ve rekabetçi fiyat avantajı sunabiliyoruz.",
        "Our new production facility represents more than an increase in capacity — it is a major step forward in digital transformation and operational excellence.\n\nThese investments enable us to optimize production costs while offering our customers sustainable, high-quality and competitively priced solutions.",
        [], 'assets/img/uretim-robotik.jpg'];

    $sections[] = ['production', 'features', 'icon-cards', 1,
        'Teknolojik Altyapımız', 'Our Technological Infrastructure',
        '', '', '', '',
        [
            'items_tr' => [
                ['icon' => 'automation', 'title' => 'Gelişmiş Otomasyon Sistemleri', 'text' => 'Robotik hatlar ve otomasyon ile kesintisiz, standart kalitede üretim.'],
                ['icon' => 'digital', 'title' => 'Dijital Üretim Altyapısı', 'text' => 'Üretimin her aşamasında dijital izleme ve veri odaklı yönetim.'],
                ['icon' => 'precision', 'title' => 'Yüksek Hassasiyetli Proses Kontrolü', 'text' => 'Uluslararası standartlarda ölçüm ve kalite güvence süreçleri.'],
                ['icon' => 'energy', 'title' => 'Enerji Verimliliği Odaklı Üretim', 'text' => 'Düşük enerji tüketimi, optimize edilmiş maliyet ve çevresel etki.'],
            ],
            'items_en' => [
                ['icon' => 'automation', 'title' => 'Advanced Automation Systems', 'text' => 'Robotic lines and automation for uninterrupted, consistent quality.'],
                ['icon' => 'digital', 'title' => 'Digital Manufacturing Infrastructure', 'text' => 'Digital monitoring and data-driven management at every stage.'],
                ['icon' => 'precision', 'title' => 'High-Precision Process Control', 'text' => 'Measurement and quality assurance to international standards.'],
                ['icon' => 'energy', 'title' => 'Energy-Efficient Production Model', 'text' => 'Lower energy consumption, optimized costs and environmental impact.'],
            ],
        ], ''];

    $sections[] = ['production', 'gallery', 'gallery', 2,
        'Tesisimizden', 'Inside Our Facility', '', '', '', '',
        [
            'images' => [
                ['src' => 'assets/img/uretim-robotik.jpg', 'alt_tr' => 'Robotik üretim hattı', 'alt_en' => 'Robotic production line'],
                ['src' => 'assets/img/hadde-hatti.jpg', 'alt_tr' => 'Sıcak hadde hattı', 'alt_en' => 'Hot rolling line'],
                ['src' => 'assets/img/sicak-cekim.jpg', 'alt_tr' => 'Soğutma platformu', 'alt_en' => 'Cooling bed'],
                ['src' => 'assets/img/tesis-vinc.jpg', 'alt_tr' => 'Üretim tesisi ve tavan vinci', 'alt_en' => 'Production facility and overhead crane'],
            ],
        ], ''];

    /* ---- SÜRDÜRÜLEBİLİRLİK ---- */
    $sections[] = ['sustainability', 'intro', 'split', 0,
        'Çevreye Duyarlı Üretim', 'Environmentally Responsible Manufacturing',
        'Şirket stratejimizin temel taşı', 'A cornerstone of our corporate strategy',
        "Çevreye duyarlı üretim anlayışı, şirket stratejimizin temel taşlarından biridir.\n\nYenilenebilir enerji yatırımlarımız sayesinde enerji maliyetlerimizi optimize ederken karbon ayak izimizi azaltıyor ve sürdürülebilir üretimi önceliklendiriyoruz.",
        "Environmental responsibility is one of the cornerstones of our corporate strategy.\n\nThrough our renewable energy investments, we optimize energy costs while reducing our carbon footprint and prioritizing sustainable manufacturing.",
        [], 'assets/img/ges-cati.jpg'];

    $sections[] = ['sustainability', 'ges', 'stats', 1,
        'Güneş Enerjisi Yatırımlarımız', 'Our Solar Power Investments',
        '', '', '', '',
        [
            'items_tr' => [
                ['value' => '3.991', 'label' => 'kWe — Yeni Tesis Çatı GES'],
                ['value' => '2.812', 'label' => 'kWe — Mevcut Tesis Çatı GES'],
                ['value' => '6.803', 'label' => 'kWe — Toplam Kurulu Güç'],
            ],
            'items_en' => [
                ['value' => '3.991', 'label' => 'MWp — New Facility Rooftop Solar'],
                ['value' => '2.812', 'label' => 'MWp — Existing Facility Rooftop Solar'],
                ['value' => '6.803', 'label' => 'MWp — Total Installed Capacity'],
            ],
        ], ''];

    $sections[] = ['sustainability', 'benefits', 'icon-cards', 2,
        'Sürdürülebilir Üretimin Kazanımları', 'The Gains of Sustainable Manufacturing',
        '', '', '', '',
        [
            'items_tr' => [
                ['icon' => 'leaf', 'title' => 'Düşük Karbon Ayak İzi', 'text' => 'Yenilenebilir enerji ile üretim kaynaklı emisyonları azaltıyoruz.'],
                ['icon' => 'energy', 'title' => 'Enerji Maliyeti Optimizasyonu', 'text' => 'GES yatırımları enerji giderlerini kalıcı olarak düşürüyor.'],
                ['icon' => 'globe', 'title' => 'Küresel Uyum', 'text' => 'Çevre dostu üretim modelleri ile uluslararası pazarlarda rekabet avantajı.'],
            ],
            'items_en' => [
                ['icon' => 'leaf', 'title' => 'Lower Carbon Footprint', 'text' => 'Renewable energy reduces our production-related emissions.'],
                ['icon' => 'energy', 'title' => 'Optimized Energy Costs', 'text' => 'Solar investments permanently reduce our energy expenses.'],
                ['icon' => 'globe', 'title' => 'Global Compliance', 'text' => 'Environmentally responsible production strengthens our position in global markets.'],
            ],
        ], ''];

    /* ---- ÜRÜNLER (sayfa girişi) ---- */
    $sections[] = ['products', 'intro', 'lead', 0,
        'Geniş Ürün Portföyü', 'An Extensive Product Portfolio',
        "Katma değerli üretim anlayışımız sayesinde özellikle çelik profil ve işlenmiş çubuk ürün gruplarında global pazarda yüksek rekabet gücüne sahibiz.",
        "Our value-added manufacturing approach enables us to maintain strong competitiveness in global markets, particularly in steel profiles and machined steel bar products.",
        '', '', [], ''];

    /* ---- SEKTÖRLER (sayfa girişi) ---- */
    $sections[] = ['industries', 'intro', 'lead', 0,
        'Birçok Sektöre Güç Veriyoruz', 'Powering Multiple Industries',
        "Sektörel çeşitlilik sayesinde ekonomik dalgalanmalara karşı dengeli ve sürdürülebilir bir büyüme modeli benimsiyoruz.",
        "Our diversified customer base enables us to achieve balanced, sustainable growth while remaining resilient against market fluctuations.",
        '', '', [], ''];

    /* ---- GLOBAL ---- */
    $sections[] = ['global', 'intro', 'split', 0,
        '5 Kıtada 100\'den Fazla Ülke', 'More Than 100 Countries Across 5 Continents',
        'İhracat, büyüme stratejimizin temelidir', 'Exports are a key pillar of our growth strategy',
        "İhracat, büyüme stratejimizin temel unsurlarındandır. Avrupa, Orta Doğu, Kuzey Afrika ve Latin Amerika başta olmak üzere 5 kıtada 100'den fazla ülkeye ihracat gerçekleştiriyoruz.",
        "Exports are one of the key pillars of our growth strategy. Today, we export to more than 100 countries across five continents, with strong market positions in Europe, the Middle East, North Africa and Latin America.",
        [], 'assets/img/kizgin-cubuk.jpg'];

    $sections[] = ['global', 'region-na', 'region', 1,
        'Kuzey Afrika', 'North Africa',
        '', '',
        "Yoğun rekabet ortamına rağmen bölgede sürdürülebilir büyüme sağlıyoruz.",
        "Despite intense competition, we continue to achieve sustainable growth in the region.",
        [
            'items_tr' => ['Yüksek kalite standardımız', 'Hızlı teslimat kabiliyetimiz', 'Teknik destek altyapımız', 'Güçlü distribütör ağımız'],
            'items_en' => ['Consistent product quality', 'Fast delivery capabilities', 'Technical support expertise', 'Strong distributor network'],
        ], ''];

    $sections[] = ['global', 'region-me', 'region', 2,
        'Orta Doğu', 'Middle East',
        '', '',
        "Orta Doğu pazarı, yeniden yapılanma ve altyapı yatırımlarıyla büyük potansiyel barındırmaktadır.\n\nIrak, Ürdün, Suudi Arabistan ve Katar başta olmak üzere bölgedeki projelere aktif sevkiyat gerçekleştiriyoruz. Lojistik avantajlarımız sayesinde zamanında ve eksiksiz teslimat sağlıyoruz.\n\nÖnümüzdeki yıllarda Orta Doğu'nun ihracat hacmimizde daha büyük bir paya sahip olması beklenmektedir.",
        "The Middle East offers significant growth potential driven by infrastructure development and reconstruction projects.\n\nWe actively supply projects across Iraq, Jordan, Saudi Arabia, Qatar and other regional markets. Thanks to our logistical advantages, we ensure timely and reliable deliveries.\n\nWe expect the Middle East to account for an even greater share of our export portfolio in the coming years.",
        [], ''];

    $sections[] = ['global', 'region-eu', 'region', 3,
        'Avrupa Birliği', 'European Union',
        '', '',
        "1 Ağustos itibarıyla uygulamaya alınan ülkeye özel kota sistemi ile Türkiye'nin AB'ye ihraç edebileceği tonaj artmıştır.",
        "With the country-specific quota system introduced on 1 August, Türkiye's export quota to the European Union has increased.",
        [
            'items_tr' => ['Daha öngörülebilir ihracat planlaması', 'Uzun vadeli kontratlar', 'Avrupa pazarında güçlenen konum'],
            'items_en' => ['More predictable export planning', 'Long-term commercial agreements', 'A stronger competitive position in the European market'],
        ], ''];

    $sections[] = ['global', 'supply', 'split-reverse', 4,
        'Tedarik & Hammadde Stratejisi', 'Raw Material Supply Strategy',
        'Hibrit kütük tedarik modeli', 'A hybrid billet sourcing strategy',
        "Kütük tedarikinde hibrit bir model uyguluyoruz. Yurt içindeki güçlü üreticilerden sağladığımız hammaddeler lojistik avantaj ve hızlı teslimat imkânı sunarken; Karadeniz bölgesi, Orta Avrupa ve Körfez ülkelerinden gerçekleştirdiğimiz ithalat ile global arz-talep dengesini optimize ediyoruz.",
        "We implement a hybrid billet sourcing strategy. Billets sourced from leading domestic producers provide logistical advantages and shorter delivery times, while imports from the Black Sea region, Central Europe and the Gulf countries enable us to optimize the global supply-demand balance.",
        [
            'items_tr' => ['Fiyat istikrarı sağlıyoruz', 'Üretim sürekliliğini garanti altına alıyoruz', 'Riskleri dengeli biçimde yönetiyoruz'],
            'items_en' => ['Maintain price stability', 'Ensure uninterrupted production', 'Effectively manage supply chain risks'],
        ], 'assets/img/hadde-hatti.jpg'];

    $sections[] = ['global', 'trade', 'dark-feature', 5,
        'Küresel Ticaret & Rekabet', 'Global Trade & Competitiveness',
        '', '',
        "Artan korumacılık politikaları, kalite ve sertifikasyon gerekliliklerini ön plana çıkarmıştır. Uluslararası standartlara uygun üretim, belgeli kalite sistemleri ve çevre dostu üretim modelleri sayesinde küresel pazarlarda rekabet avantajımızı koruyoruz.\n\nABD pazarında uygulanan yüksek vergiler ve antidamping önlemleri standart ürünlerde zorluk oluştursa da, yüksek katma değerli ve özel mühendislik gerektiren ürün gruplarında fırsatları değerlendirmeye devam ediyoruz.",
        "As protectionist trade policies continue to expand worldwide, product quality, certification and compliance have become increasingly important. We maintain our competitive advantage through manufacturing to international standards, certified quality management systems and environmentally responsible production practices.\n\nAlthough high tariffs and anti-dumping measures in the U.S. market create challenges for commodity-grade steel products, we continue to pursue opportunities in value-added and engineering-intensive product segments.",
        [], 'assets/img/kizgin-cubuk.jpg'];

    /* ---- İLETİŞİM ---- */
    $sections[] = ['contact', 'intro', 'lead', 0,
        'Size Nasıl Yardımcı Olabiliriz?', 'How Can We Help You?',
        "Ürünlerimiz, ihracat süreçlerimiz veya iş birliği fırsatları hakkında bilgi almak için bize ulaşın.",
        "Contact us for information about our products, export operations or partnership opportunities.",
        '', '', [], ''];

    foreach ($sections as $s) {
        $db->insert('sections', [
            'page_id'     => $pageIds[$s[0]],
            'skey'        => $s[1],
            'type'        => $s[2],
            'sort_order'  => $s[3],
            'title_tr'    => $s[4],
            'title_en'    => $s[5],
            'subtitle_tr' => $s[6],
            'subtitle_en' => $s[7],
            'body_tr'     => $s[8],
            'body_en'     => $s[9],
            'data_json'   => $s[10] ? json_encode($s[10], JSON_UNESCAPED_UNICODE) : '',
            'image'       => $s[11],
            'is_published'=> 1,
        ]);
    }

    /* ================= KATEGORİLER ================= */
    // Eski sitedeki 3 ürün kategorisi
    $categories = [
        ['sicak-cekilmis-urunler', 'hot-rolled-products', 'Sıcak Çekilmiş Ürünler', 'Hot-Rolled Products'],
        ['profiller', 'profiles', 'Profiller', 'Profiles'],
        ['diger-urunler', 'other-products', 'Diğer Ürünler', 'Other Products'],
    ];
    $catIds = [];
    foreach ($categories as $i => $c) {
        $catIds[$c[0]] = $db->insert('categories', [
            'slug_tr' => $c[0], 'slug_en' => $c[1],
            'name_tr' => $c[2], 'name_en' => $c[3],
            'sort_order' => $i, 'is_published' => 1,
        ]);
    }

    /* Ürün → kategori dağılımı (ürün slug'ına göre) */
    $productCategory = [
        'nervurlu-insaat-demiri'    => 'sicak-cekilmis-urunler',
        'kosebent'                  => 'sicak-cekilmis-urunler',
        'lama'                      => 'sicak-cekilmis-urunler',
        'kare'                      => 'sicak-cekilmis-urunler',
        'duz-yuvarlak'              => 'sicak-cekilmis-urunler',
        'altikose'                  => 'sicak-cekilmis-urunler',
        'npu'                       => 'profiller',
        'npi'                       => 'profiller',
        'ipe'                       => 'profiller',
        'hea'                       => 'profiller',
        'heb'                       => 'profiller',
        'transmisyon-mili'          => 'diger-urunler',
        'nervurlu-lama-celik-serit' => 'diger-urunler',
        'izli-kare'                 => 'diger-urunler',
        'izli-lama'                 => 'diger-urunler',
    ];

    /* ================= ÜRÜNLER ================= */
    // Eski isikcelik.com'dan birebir aktarılan ürünler (ad, görsel, ölçü tablosu)
    $importFile = __DIR__ . '/products-import.json';
    $products = is_file($importFile) ? (json_decode(file_get_contents($importFile), true) ?: []) : [];
    foreach ($products as $p) {
        $p['body_tr'] = $p['body_tr'] ?? '';
        $p['body_en'] = $p['body_en'] ?? '';
        $p['category_id'] = $catIds[$productCategory[$p['slug_tr']] ?? ''] ?? 0;
        $p['is_published'] = 1;
        $db->insert('products', $p);
    }

    /* ================= SEKTÖRLER ================= */
    $sectors = [
        ['İnşaat', 'Construction', 'Yapısal çelik, profil ve donatı ürünleriyle inşaat sektörünün güvenilir tedarikçisiyiz.', 'A reliable supplier of structural steel, profiles and reinforcement products for construction.', 'construction'],
        ['Otomotiv', 'Automotive', 'Hassas toleranslı soğuk çekim ve işlenmiş çubuk ürünlerimiz otomotiv yan sanayisinde kullanılır.', 'Our precision cold-drawn and machined bar products serve the automotive supply industry.', 'automotive'],
        ['Makine', 'Machinery', 'Makine imalatının ihtiyaç duyduğu dayanıklı ve işlenebilir çelik çözümleri sunuyoruz.', 'We provide durable, machinable steel solutions for machinery manufacturing.', 'machinery'],
        ['Tarım Ekipmanları', 'Agricultural Equipment', 'Zorlu koşullara dayanıklı çelik ürünlerimiz tarım makineleri üretiminde tercih edilir.', 'Our steel products, built for demanding conditions, are preferred in agricultural machinery production.', 'agriculture'],
        ['Mobilya Sanayi', 'Furniture Manufacturing', 'Profil ve çubuk ürünlerimiz metal mobilya üretiminin temel girdilerindendir.', 'Our profiles and bars are key inputs for metal furniture production.', 'furniture'],
        ['Sanayi Yatırımları', 'Industrial Investments', 'Endüstriyel tesis ve altyapı projelerine geniş ürün portföyümüzle çözüm ortağıyız.', 'A solution partner for industrial facilities and infrastructure projects with our extensive portfolio.', 'industry'],
    ];
    foreach ($sectors as $i => $s) {
        $db->insert('sectors', [
            'name_tr' => $s[0], 'name_en' => $s[1],
            'desc_tr' => $s[2], 'desc_en' => $s[3],
            'icon' => $s[4], 'image' => '',
            'sort_order' => $i, 'is_published' => 1,
        ]);
    }

    /* ================= HABERLER ================= */
    $news = [
        [
            'slug_tr' => 'yeni-uretim-tesisimiz-devrede', 'slug_en' => 'new-production-facility-commissioned',
            'title_tr' => 'Yeni Üretim Tesisimiz Devrede: Kapasitemiz Üç Katına Çıktı',
            'title_en' => 'Our New Production Facility Is Live: Capacity Tripled',
            'summary_tr' => 'Yeni nesil üretim tesisimizin devreye girmesiyle yıllık üretim kapasitemiz 450.000 tona ulaştı.',
            'summary_en' => 'With the commissioning of our next-generation facility, our annual production capacity has reached 450,000 tons.',
            'body_tr' => "Yeni üretim tesisimizin devreye girmesiyle birlikte üretim kapasitemizi üç katına çıkararak yıllık 450.000 tona ulaştık.\n\nYeni tesisimiz; gelişmiş otomasyon sistemleri, dijital üretim altyapısı ve yüksek hassasiyetli proses kontrolü ile donatıldı. Bu yatırım yalnızca kapasite artışı değil; aynı zamanda dijital dönüşüm ve operasyonel verimlilik anlamına geliyor.\n\nBu sayede hem maliyet optimizasyonu sağlıyor hem de müşterilerimize sürdürülebilir ve rekabetçi fiyat avantajı sunabiliyoruz.",
            'body_en' => "With the commissioning of our new production facility, we have tripled our production capacity to 450,000 tons per year.\n\nThe new facility is equipped with advanced automation systems, digital manufacturing infrastructure and high-precision process control. This investment represents more than an increase in capacity — it is a major step forward in digital transformation and operational excellence.\n\nAs a result, we optimize production costs while offering our customers sustainable and competitively priced solutions.",
            'image' => 'assets/img/uretim-robotik.jpg',
            'meta_title_tr' => 'Yeni Üretim Tesisimiz Devrede | Işık Çelik',
            'meta_title_en' => 'New Production Facility Commissioned | Işık Çelik',
            'meta_desc_tr' => 'Işık Çelik yeni üretim tesisiyle kapasitesini üç katına çıkardı: yıllık 450.000 ton. Otomasyon ve dijital üretim altyapısıyla yeni bir dönem.',
            'meta_desc_en' => 'Işık Çelik tripled its capacity to 450,000 tons per year with its new facility — a new era of automation and digital manufacturing.',
            'published_at' => '2026-06-15',
        ],
        [
            'slug_tr' => 'ges-yatirimlarimiz-tamamlandi', 'slug_en' => 'solar-power-investments-completed',
            'title_tr' => '6.803 kWe Güneş Enerjisi Yatırımımız Tamamlandı',
            'title_en' => 'Our 6.803 MWp Solar Power Investment Is Complete',
            'summary_tr' => 'Çatı GES yatırımlarımızla toplam 6.803 kWe yenilenebilir enerji üretim gücüne ulaştık.',
            'summary_en' => 'With our rooftop solar investments, we have reached a total renewable capacity of 6.803 MWp.',
            'body_tr' => "Sürdürülebilir üretim stratejimizin önemli bir adımı olan güneş enerjisi yatırımlarımız tamamlandı.\n\nYeni tesisimizin çatısında 3.991 kWe, mevcut tesisimizde ise 2.812 kWe olmak üzere toplam 6.803 kWe kurulu güce ulaştık.\n\nBu yatırımlar sayesinde enerji maliyetlerimizi optimize ederken karbon ayak izimizi azaltıyor ve çevre dostu çelik üretimini önceliklendiriyoruz.",
            'body_en' => "Our solar power investments — a key step in our sustainable manufacturing strategy — have been completed.\n\nWe have reached a total installed capacity of 6.803 MWp: 3.991 MWp on the roof of our new facility and 2.812 MWp at our existing facility.\n\nThese investments allow us to optimize energy costs while reducing our carbon footprint and prioritizing environmentally responsible steel production.",
            'image' => 'assets/img/ges-cati.jpg',
            'meta_title_tr' => 'GES Yatırımlarımız Tamamlandı | Işık Çelik',
            'meta_title_en' => 'Solar Power Investments Completed | Işık Çelik',
            'meta_desc_tr' => 'Işık Çelik toplam 6.803 kWe kurulu güce sahip çatı GES yatırımlarını tamamladı. Sürdürülebilir çelik üretiminde yeni bir adım.',
            'meta_desc_en' => 'Işık Çelik completed rooftop solar investments totalling 6.803 MWp — another step towards sustainable steel manufacturing.',
            'published_at' => '2026-07-10',
        ],
    ];
    foreach ($news as $n) {
        $n['is_published'] = 1;
        $n['created_at'] = $now;
        $db->insert('news', $n);
    }
}
