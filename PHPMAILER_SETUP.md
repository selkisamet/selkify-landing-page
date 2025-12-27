# PHPMailer Manuel Kurulum (Composer'sız)

## ✅ Tamamlandı

PHPMailer artık **Composer olmadan** manuel yükleme ile çalışıyor!

## 📁 Klasör Yapısı

```
selkify-landingpage/
├── phpmailer/              ← PHPMailer klasörü
│   ├── src/
│   │   ├── Exception.php
│   │   ├── PHPMailer.php
│   │   └── SMTP.php
│   └── language/
├── config.php
├── send-email.php          ← Güncellendi (manuel yükleme)
├── index.html
├── script.js
└── style.css
```

## 🔧 Yapılan Değişiklikler

### 1. send-email.php Güncellendi

**Önceki (Composer ile):**
```php
require_once 'vendor/autoload.php';
```

**Yeni (Manuel):**
```php
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';
```

### 2. .gitignore Güncellendi

- ✅ `phpmailer/` klasörü artık Git'e dahil
- ❌ `vendor/` klasörü ignore edildi (kullanılmıyor)
- ❌ Composer dosyaları ignore edildi (gerekli değil)

## 📦 PHPMailer Versiyonu

- **Version:** 6.1.5
- **Kaynak:** Manuel yükleme (phpmailer/ klasörü)

## 🧪 Test

Test dosyasını çalıştırın:
```
http://localhost/selkify-landingpage/test-phpmailer.php
```

veya

```bash
php test-phpmailer.php
```

**Beklenen Sonuç:**
- ✅ Tüm dosyalar bulundu
- ✅ PHPMailer instance oluşturuldu
- ✅ Test maili gönderildi

## 🚀 Production'a Deploy

### Upload Edilecek Dosyalar:

1. **phpmailer/** klasörü (tüm içeriğiyle)
   ```
   phpmailer/src/Exception.php
   phpmailer/src/PHPMailer.php
   phpmailer/src/SMTP.php
   ```

2. **send-email.php** (güncellenmiş versiyon)

3. **config.php** (şifre ile)

4. Diğer dosyalar (index.html, script.js, style.css)

### FTP ile Upload:

```
/public_html/
├── phpmailer/
│   └── src/
│       ├── Exception.php
│       ├── PHPMailer.php
│       └── SMTP.php
├── config.php
├── send-email.php
├── index.html
├── script.js
└── style.css
```

### Dosya İzinleri:

```bash
chmod 755 phpmailer/
chmod 644 phpmailer/src/*.php
chmod 644 send-email.php
chmod 644 config.php
```

## ✅ Avantajları

1. **Composer Gereksiz** - Hosting'de Composer kurulumu gerekmez
2. **Daha Hızlı** - Sadece gerekli dosyalar yüklenir
3. **Daha Basit** - vendor/ klasörü yok
4. **Git Kontrolü** - Tüm dosyalar version control altında

## 📝 Notlar

- PHPMailer klasörü tüm sunucularda çalışır
- Composer gerektirmez
- PHP 5.5+ ile uyumlu
- Production'da sorunsuz çalışır

## 🔍 Sorun Giderme

### "Class not found" Hatası:

**Neden:** PHPMailer dosyaları yüklenememiş.

**Çözüm:**
```php
// send-email.php dosyasında şu satırların olduğundan emin olun:
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';
```

### Path Hatası:

**Neden:** phpmailer/ klasörü yanlış yerde.

**Çözüm:** phpmailer/ klasörünün send-email.php ile aynı dizinde olduğundan emin olun.

### Test:

```bash
# Dosyaların var olduğunu kontrol et
ls -la phpmailer/src/

# PHP'den test et
php -r "require 'phpmailer/src/PHPMailer.php'; echo 'OK';"
```

## ✅ Checklist

Production'a göndermeden önce:

- [ ] phpmailer/ klasörü var
- [ ] phpmailer/src/ içinde 3 dosya var (Exception.php, PHPMailer.php, SMTP.php)
- [ ] send-email.php güncellendi (manuel require)
- [ ] test-phpmailer.php çalıştırıldı ve başarılı
- [ ] config.php şifresi ayarlandı
- [ ] Form test edildi ve mail geldi

---

**Son Güncelleme:** 28 Aralık 2024
**Durum:** ✅ Çalışıyor (Composer olmadan)
