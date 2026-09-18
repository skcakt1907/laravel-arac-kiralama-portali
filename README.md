# Arac Kiralama ve Magaza Portali

Arac kiralama, uyelik ve magaza modullerini bir arada sunan portal.

## Ozellikler

- Arac listeleme, kiralama talebi ve rezervasyon yonetimi
- Uyelik sistemi ve uye paneli
- Magaza modulu, blog ve kurumsal sayfalar
- Yonetim paneli ve e-posta bildirimleri

## Kullanilan teknolojiler

Laravel 13 - PHP 8.3 - MySQL - Blade

## Bu depo hakkinda

Gercek bir musteri projesinin **portfolyo icin yayinlanmis** surumudur.
Yayina hazirlanirken canli alan adlari, gercek iletisim bilgileri, musteri
kayitlari ve uygulama anahtarlari ornek degerlerle degistirilmistir.
Kod ve mimari oldugu gibidir; veri gercek degildir.

## Kurulum

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
