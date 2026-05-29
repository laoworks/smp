# Panduan Hosting

Panduan ini disiapkan untuk deploy project Laravel 12 `smp01namrole` ke hosting produksi, terutama shared hosting, cPanel, atau VPS berbasis Apache/Nginx.

## Ringkasan Kebutuhan

- PHP `8.2` atau lebih baru
- MySQL / MariaDB
- Composer
- Node.js dan npm hanya dibutuhkan saat build asset
- Extension PHP umum Laravel:
  - `bcmath`
  - `ctype`
  - `fileinfo`
  - `json`
  - `mbstring`
  - `openssl`
  - `pdo`
  - `pdo_mysql`
  - `tokenizer`
  - `xml`
  - `gd` atau `imagick`

## File Penting

- Contoh environment hosting: `.env.hosting.example`
- Document root aplikasi: folder `public`
- Asset production hasil build: `public/build`

## Langkah Deploy

1. Salin project ke server.
2. Copy `.env.hosting.example` menjadi `.env`.
3. Isi nilai berikut sesuai server:
   - `APP_URL`
   - `APP_KEY`
   - `DB_*`
   - `MAIL_*`
4. Jika `APP_KEY` belum ada, jalankan:

```bash
php artisan key:generate
```

5. Install dependency production:

```bash
composer install --no-dev --optimize-autoloader
```

6. Build asset production di lokal atau server:

```bash
npm install
npm run build
```

7. Upload hasil folder `public/build` jika build dilakukan di lokal.
8. Jalankan migrasi database:

```bash
php artisan migrate --force
```

9. Buat symbolic link storage:

```bash
php artisan storage:link
```

10. Jalankan optimasi production:

```bash
composer run deploy:shared-hosting
```

## Konfigurasi Shared Hosting / cPanel

Ada dua pola umum:

### Opsi 1: Document Root diarahkan ke `public`

Ini opsi terbaik.

- Upload seluruh project ke satu folder, misalnya:
  - `/home/user/smp01namrole`
- Arahkan domain atau subdomain ke:
  - `/home/user/smp01namrole/public`

Dengan cara ini, `index.php` dan `.htaccess` di `public` langsung bekerja normal.

### Opsi 2: Jika document root harus `public_html`

Jika hosting tidak mengizinkan penggantian document root:

- Simpan seluruh project Laravel di luar `public_html`, misalnya:
  - `/home/user/smp01namrole`
- Pindahkan isi folder `public` ke `public_html`
- Sesuaikan path pada `index.php` di `public_html` agar menunjuk ke:
  - `../smp01namrole/vendor/autoload.php`
  - `../smp01namrole/bootstrap/app.php`

Gunakan opsi ini hanya jika benar-benar tidak bisa mengarahkan document root ke folder `public`.

## Permission Folder

Pastikan folder berikut bisa ditulis oleh web server:

- `storage`
- `bootstrap/cache`

Contoh di Linux:

```bash
chmod -R 775 storage bootstrap/cache
```

## Queue dan Cache

Konfigurasi default untuk hosting biasa sudah disiapkan di `.env.hosting.example`:

- `QUEUE_CONNECTION=sync`
- `CACHE_STORE=file`
- `SESSION_DRIVER=file`

Ini lebih aman untuk shared hosting jika tidak ada worker background.

Jika nanti server sudah mendukung worker atau supervisor, Anda bisa ubah ke:

- `QUEUE_CONNECTION=database`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`

Lalu jalankan worker queue secara terpisah.

## Checklist Sebelum Live

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` sudah sesuai domain final
- Database production sudah terhubung
- Folder `public/build` sudah ada
- `php artisan storage:link` sudah berhasil
- `composer run deploy:shared-hosting` sudah dijalankan
- Halaman frontend, login, admin, upload gambar, dan export PDF sudah dites

## Command Berguna Setelah Update

Jika ada update file di server:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
composer run deploy:shared-hosting
```

Jika butuh membersihkan cache lama:

```bash
php artisan optimize:clear
```
