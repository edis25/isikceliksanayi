# Işık Çelik — Kurumsal Web Sitesi

isikcelik.com için sıfırdan geliştirilen, iki dilli (TR ana dil + EN), yönetim panelli, SEO odaklı kurumsal web sitesi.

- **Teknoloji:** Saf PHP 8 (7.4+ uyumlu) + PDO. Framework ve Composer bağımlılığı yok; her cPanel/paylaşımlı hostingde çalışır.
- **Veritabanı:** Üretimde MySQL, yerel geliştirmede SQLite (tek config değişikliği).
- **Tasarım:** gruppobeltrame.com'dan ilhamla koyu sinematik hero (video) + açık içerik bölümleri, kızgın çelik turuncusu vurgu rengi.

## Yerel Geliştirme

```bash
php install.php          # şema + başlangıç içeriği (SQLite: data/site.db)
php -S localhost:8080 router.php
```

- Site: http://localhost:8080 — EN: http://localhost:8080/en/
- Panel: http://localhost:8080/admin/ — kullanıcı: `admin`, parola: `IsikCelik!2026` (ilk girişte değiştirin!)
- Sıfırlamak için: `php install.php --fresh`

## Hostinge Kurulum (cPanel)

1. Depo içeriğini `public_html` köküne yükleyin (`data/` klasörü hariç tutulabilir).
2. cPanel'den bir MySQL veritabanı + kullanıcı oluşturun.
3. `app/config.sample.php` dosyasını `app/config.php` olarak kopyalayıp düzenleyin:
   - `driver: 'mysql'`, host/name/user/pass bilgileri
   - `base_url: 'https://isikcelik.com'`, `env: 'production'`
4. Tarayıcıdan `https://alanadi.com/install.php?key=isik-kurulum` adresini açın (şema + içerik yüklenir).
5. **`install.php` dosyasını sunucudan silin.**
6. `/admin/` adresinden girip `admin` parolasını değiştirin.
7. `.htaccess` içindeki HTTPS/www yönlendirme satırlarının yorumunu açın.
8. `robots.txt` içindeki Sitemap URL'sini kontrol edin.

## Dizin Yapısı

| Yol | Açıklama |
|---|---|
| `index.php` | Front controller + router (dil algılama, temiz URL) |
| `app/` | Config, PDO katmanı, yardımcılar, dil dosyası, seed |
| `templates/` | Ön yüz şablonları (`page-*.php`) + partial'lar |
| `admin/` | Yönetim paneli (bağımsız oturum, kendi CSS'i) |
| `assets/` | CSS, JS, optimize görseller, hero videosu |
| `uploads/` | Panelden yüklenen medya |
| `sitemap.php` | `/sitemap.xml` çıktısı (dinamik, hreflang'li) |

## Yönetim Paneli Modülleri

Sayfalar & bölümler (TR/EN sekmeli), Ürünler, Sektörler, Haberler, Medya kütüphanesi (otomatik 1920px küçültme), İletişim mesajları, Site ayarları (telefon, adres, sosyal medya, Google Analytics, Maps embed), Kullanıcılar.

## SEO Özellikleri

- Sayfa/kayıt bazında meta title & description (panelden yönetilir)
- `hreflang` (tr / en / x-default) + canonical + Open Graph + Twitter Card
- JSON-LD: Organization, Product, NewsArticle
- Dinamik `sitemap.xml`, `robots.txt`, 404, temiz URL'ler
- Lazy-load görseller, gzip + tarayıcı önbelleği (.htaccess)

## Notlar

- Hero videosu (`assets/video/hero.mp4`, ~4MB) tanıtım filminden kesilmiş 19 sn'lik sessiz döngüdür; değiştirmek için aynı yola yeni dosya koymanız yeterli.
- İletişim formu kayıtları panelde "Mesajlar" bölümüne düşer (e-posta bildirimi eklenmek istenirse `index.php` içindeki form işleyicisine `mail()` eklenebilir).
