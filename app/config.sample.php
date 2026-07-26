<?php
/**
 * Işık Çelik — yapılandırma örneği.
 * Bu dosyayı config.php olarak kopyalayıp sunucunuza göre düzenleyin.
 */
return [
    // 'sqlite' (lokal geliştirme) veya 'mysql' (üretim / cPanel)
    'db' => [
        'driver'      => 'mysql',
        'host'        => 'localhost',
        'name'        => 'isikcelik_db',
        'user'        => 'isikcelik_user',
        'pass'        => 'GUCLU_PAROLA',
        'sqlite_path' => __DIR__ . '/../data/site.db',
    ],

    // Üretimde tam alan adı (sonda / olmadan). Boş bırakılırsa otomatik algılanır.
    'base_url' => 'https://isikcelik.com',

    // 'production' | 'local' — production'da hatalar ekrana basılmaz
    'env' => 'production',
];
