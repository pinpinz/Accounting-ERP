# Accounting ERP (Laravel + AdminLTE + PostgreSQL)

A simple multi-company ERP keuangan built with **Laravel 12**, **AdminLTE 4**, and **PostgreSQL**. Setelah login, pengguna dapat memilih salah satu dari 5 perusahaan (atau mode konsolidasi) untuk melihat dashboard, mencatat transaksi, dan menghasilkan laporan laba rugi, neraca saldo, neraca, serta arus kas sederhana.

## Fitur utama
- **Login & otentikasi** via Laravel Breeze (Blade) dengan tampilan AdminLTE.
- **Pemilihan perusahaan** setelah login (konsolidasi semua atau per perusahaan).
- **Transaksi keuangan** inflow/outflow dengan CoA bertipe aktiva, pasiva, ekuitas, pendapatan, dan beban.
- **Dashboard grafis** (Chart.js) untuk tren pemasukan/pengeluaran dan komposisi kategori.
- **Laporan**: Laba Rugi, Neraca Saldo, Neraca (Balance Sheet), dan Arus Kas sederhana.
- **Seed data demo**: 5 perusahaan, akun dasar, transaksi contoh, dan pengguna `admin@example.com` / `password`.

## Persiapan lingkungan
1. **Salin konfigurasi**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
2. **Atur PostgreSQL** di `.env` (default: `accounting_erp`, user `postgres`, password `secret`).
3. **Install dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```
4. **Migrasi & seed**
   ```bash
   php artisan migrate --seed
   ```
5. **Jalankan dev server**
   ```bash
   php artisan serve
   ```

## Alur penggunaan
1. Login menggunakan kredensial demo.
2. Pilih perusahaan (atau konsolidasi) di menu **Pilih Perusahaan**.
3. Lihat ringkasan kas dan grafik di Dashboard.
4. Catat pemasukan/pengeluaran di menu **Transaksi** dengan filter deskripsi, tanggal, arah kas, dan tipe akun.
5. Buka menu **Laporan** untuk Laba Rugi, Neraca Saldo, Neraca, dan Arus Kas sederhana.

## Struktur utama
- `app/Models` — Company, Account, Transaction, relasi User.
- `app/Http/Controllers` — Dashboard, Transaction, Report, Company selector.
- `database/migrations` — skema perusahaan, akun, transaksi, pivot user-company, sesi.
- `database/seeders/DemoDataSeeder.php` — seed 5 perusahaan + transaksi contoh.
- `resources/views` — layout AdminLTE, dashboard, transaksi, laporan, pemilihan perusahaan.

## Push ke GitHub
Jika remote belum disetel, gunakan skrip bantu:
```bash
export GIT_REMOTE_URL="https://github.com/<owner>/<repo>.git"
./scripts/push-to-remote.sh
```

## Catatan
- Seeder membuat akun admin: **admin@example.com / password**.
- Pagination menggunakan Bootstrap 5 agar konsisten dengan AdminLTE.
- Sesuaikan Chart.js/tema AdminLTE melalui `resources/views/layouts/app.blade.php` dan `resources/js/app.js`.
