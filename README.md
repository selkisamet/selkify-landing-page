# Selkify Landing Page

Profesyonel web tasarım hizmetleri sunan Selkify için geliştirilmiş modern landing page.

## Özellikler

- 📱 Mobil uyumlu tasarım
- ⚡ Hızlı yüklenme (WebP görüntü optimizasyonu)
- 🎨 Modern ve responsive arayüz
- 📧 PHPMailer ile çalışan iletişim formu
- 🔍 SEO optimizasyonu
- 📊 Google Analytics ve Yandex Metrica entegrasyonu
- ♿ Erişilebilirlik standartlarına uygun

## Kurulum

### 1. Gereksinimler

- PHP 7.4 veya üzeri
- Composer
- Web sunucusu (Apache/Nginx)
- SMTP mail sunucusu erişimi

### 2. Projeyi Klonlayın

```bash
git clone [repository-url]
cd selkify-landingpage
```

### 3. Bağımlılıkları Yükleyin

```bash
composer install
```

### 4. Yapılandırma

`config.php` dosyasını açın ve mail sunucusu bilgilerinizi girin:

```php
// Email Account Credentials
define('SMTP_USERNAME', 'info@selkify.com');
define('SMTP_PASSWORD', 'YOUR_PASSWORD_HERE'); // Şifrenizi buraya yazın
```

#### SMTP Ayarları

Dosyada aşağıdaki ayarlar bulunmaktadır:

- **SMTP_HOST**: Mail sunucusu adresi (mail.selkify.com)
- **SMTP_PORT**: Port numarası (587 for TLS, 465 for SSL)
- **SMTP_SECURE**: Güvenlik protokolü ('tls' veya 'ssl')
- **SMTP_USERNAME**: Mail hesabı kullanıcı adı
- **SMTP_PASSWORD**: Mail hesabı şifresi

#### İzin Verilen Domain'ler

Güvenlik için izin verilen domain'leri `config.php` dosyasında güncelleyin:

```php
define('ALLOWED_ORIGINS', ['https://webtasarim.selkify.com', 'http://localhost']);
```

### 5. Dosya İzinleri

Aşağıdaki dizinlerin yazılabilir olduğundan emin olun:

```bash
chmod 755 send-email.php
chmod 644 config.php
```

**ÖNEMLİ**: `config.php` dosyası hassas bilgiler içerir. `.gitignore` dosyasında bu dosya zaten listelenmiştir. Asla bu dosyayı public repository'ye yüklemeyin.

## Kullanım

### İletişim Formu

İletişim formu şu alanları içerir:

- İsim (zorunlu)
- Telefon (zorunlu, otomatik formatlanır)
- E-posta (zorunlu, validasyon yapılır)
- Konu (zorunlu)
- Mesaj (zorunlu, min 10 karakter)

#### Form Özellikleri

- ✅ AJAX ile asenkron gönderim
- ✅ Gerçek zamanlı validasyon
- ✅ Telefon numarası otomatik formatlanır
- ✅ Rate limiting (60 saniyede 1 gönderim)
- ✅ CSRF koruması
- ✅ XSS koruması
- ✅ HTML formatlı e-posta şablonu

### Test Etme

1. Tarayıcınızda projeyi açın
2. İletişim formuna gidin
3. Tüm alanları doldurun
4. "Ücretsiz Teklif Al" butonuna tıklayın
5. Başarı mesajını bekleyin

## Dosya Yapısı

```
selkify-landingpage/
├── images/              # Görüntü dosyaları
├── vendor/              # Composer bağımlılıkları
├── config.php           # Mail yapılandırma dosyası
├── send-email.php       # Form işleyici
├── index.html           # Ana sayfa
├── style.css            # Stil dosyası
├── script.js            # JavaScript dosyası
├── composer.json        # Composer yapılandırması
├── .htaccess            # Apache yapılandırması
├── .gitignore           # Git ignore kuralları
└── README.md            # Bu dosya
```

## Güvenlik

### Yapılan Güvenlik Önlemleri

1. **Input Sanitization**: Tüm form verileri temizlenir
2. **Email Validation**: E-posta adresleri doğrulanır
3. **XSS Protection**: HTML özel karakterler encode edilir
4. **CSRF Protection**: Origin kontrolü yapılır
5. **Rate Limiting**: Spam koruması (60 saniye/gönderim)
6. **SQL Injection**: PDO ve prepared statements (eğer veritabanı kullanılırsa)

### Önerilen Ek Güvenlik

- SSL sertifikası kullanın (HTTPS)
- `config.php` dosyasını web erişiminden koruyun
- Düzenli olarak bağımlılıkları güncelleyin: `composer update`
- Güçlü mail şifreleri kullanın

## Troubleshooting

### E-posta Gönderilmiyor

1. `config.php` dosyasındaki SMTP ayarlarını kontrol edin
2. Debug modunu açın: `define('EMAIL_DEBUG', 2);`
3. Firewall/güvenlik duvarı SMTP portunu engelliyor olabilir
4. Mail sunucusu kullanıcı adı ve şifresini kontrol edin

### Form Gönderiminde Hata

1. Browser console'da hata mesajlarını kontrol edin
2. `send-email.php` dosyasının yazılabilir olduğundan emin olun
3. PHP session desteğinin aktif olduğunu kontrol edin
4. PHP error_log dosyasını kontrol edin

## Performans

- ✅ WebP görüntü formatı kullanımı
- ✅ Lazy loading
- ✅ Minified CSS/JS (production için)
- ✅ DNS prefetch
- ✅ Preload kritik kaynaklar
- ✅ Deferred analytics loading

## Tarayıcı Desteği

- Chrome (son 2 versiyon)
- Firefox (son 2 versiyon)
- Safari (son 2 versiyon)
- Edge (son 2 versiyon)
- Opera (son 2 versiyon)

## Lisans

Bu proje Selkify için özel olarak geliştirilmiştir.

## Destek

Sorularınız için: info@selkify.com

## Güncellemeler

### Version 1.0.1 (2024-12-28)

**Bug Fixes:**
- ✅ Form buton "Gönderiliyor..." kalma sorunu düzeltildi
- ✅ JavaScript null safety kontrolleri eklendi
- ✅ ALLOWED_ORIGINS listesi güncellendi (www alt domain eklendi)
- ✅ Debug mode production için kapatıldı

**Improvements:**
- ✅ Detaylı error logging eklendi
- ✅ SMTP bağlantı test araçları eklendi
- ✅ Deployment dokümantasyonu eklendi

### Version 1.0.0 (2024-12-28)

**Initial Release:**
- ✅ PHPMailer entegrasyonu
- ✅ AJAX form gönderimi
- ✅ Responsive tasarım
- ✅ SEO optimizasyonu
- ✅ Analytics entegrasyonu
- ✅ XSS ve CSRF koruması
- ✅ Rate limiting
- ✅ Email validasyonu
