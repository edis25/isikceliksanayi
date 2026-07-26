<?php
// Lokal geliştirme yapılandırması — üretimde config.sample.php'yi temel alın.
return [
    'db' => [
        'driver'      => 'sqlite',
        'host'        => 'localhost',
        'name'        => '',
        'user'        => '',
        'pass'        => '',
        'sqlite_path' => __DIR__ . '/../data/site.db',
    ],
    'base_url' => '',
    'env'      => 'local',
];
