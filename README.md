# SMP Negeri 01 Namrole

Project ini adalah website sekolah berbasis Laravel 12 yang mencakup:

- frontend publik sekolah
- halaman profil, berita, galeri, prestasi, guru, alumni, dan PPDB
- panel admin untuk CRUD data sekolah
- form kontak dan pendaftaran online
- export Excel dan PDF untuk data admin tertentu

## Kebutuhan Sistem

- PHP `^8.2`
- Composer
- Node.js dan npm
- MySQL / MariaDB

## Menjalankan di Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```

## Build Production

```bash
npm run build
composer run deploy:shared-hosting
```

Script `deploy:shared-hosting` akan menjalankan:

- `php artisan optimize:clear`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

## Panduan Hosting

Lihat panduan lengkap di [HOSTING.md](HOSTING.md).

## File Environment Hosting

Contoh environment production tersedia di:

- `.env.hosting.example`
