# Program Formula — Sistem Produksi Pakan & Laporan Harian

Aplikasi manajemen produksi pakan ternak berbasis web (Laravel). Digunakan untuk mengelola resep (konsep), produksi, komposisi bahan, perhitungan dosis, splitting batch produksi, serta pencatatan laporan harian (Laporan Sore).

## Fitur Utama

### Master Data
- **Units** — Satuan bahan (kg, gram, ton, sak, dll.)
- **Items** — Bahan pokok (Jagung, Beras, Kedelai), vitamin, obat
- **Locations** — Lokasi kandang/pabrik
- **Cages** — Data kandang per lokasi
- **Concepts** — Resep dasar dengan komposisi bahan (item + persentase + berat)

### Productions
- **Produksi Biasa & Pengobatan** — Buat produksi berdasarkan konsep dengan target weight
- **Snapshot & Scaling** — Generate item produksi otomatis scaling dari konsep
- **Golongan (Groups)** — Grouping item tambahan secara global
- **Tab (Split Batch)** — Bagi produksi menjadi beberapa batch dengan subtotal weight
- **Dosis Kalkulator** — Input dosis dalam gram, otomatis konversi ke kg
- **PDF Export** — Cetak laporan produksi & pengobatan

### Laporan Sore
Aplikasi pencatatan harian dengan 4 section:
- **Sisa Kemarin** — Stok dari hari sebelumnya (H-1)
- **Campuran Hari Ini** — Hasil campuran pada hari laporan
- **Kirim Hari Ini** — Barang yang dikirim pada hari laporan
- **Stock** — Stok akhir yang tersedia

Setiap section mendukung:
- Banyak kandang per section
- Banyak detail konsep per kandang
- Multi-select item tambahan per detail konsep
- Filter kandang otomatis berdasarkan lokasi

### Dashboard
- Ringkasan produksi aktif dan total
- Tabel produksi dengan filter status
- Manajemen laporan harian (CRUD)

## Teknologi

- **Backend**: Laravel 12, PHP 8.4+
- **Frontend**: Blade, CSS (design system custom), Vanilla JS
- **Database**: SQLite / MySQL
- **PDF**: DomPDF (barryvdh/laravel-dompdf)

## Instalasi

```bash
git clone <repository-url>
cd "2. PROGRAM FORMULA"
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Login: `test@example.com` (jika memakai seeder default)

## Panduan Kontribusi

Project ini menggunakan struktur branch:
- `main` — Branch production (stable)
- `develop` — Branch pengembangan
- `revisi/*` — Branch per revisi fitur

## Lisensi

Hak cipta milik **Program Formula**.
