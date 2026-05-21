# BU VANIA - Sistem Produksi Pakan

Aplikasi manajemen produksi pakan ternak berbasis web (Laravel). Digunakan untuk mengelola resep (concept), produksi, komposisi bahan, perhitungan dosis, serta splitting batch produksi.

## Fitur Utama

- **Master Data**: Units, Items (bahan pokok, vitamin, obat)
- **Concept (Resep Dasar)**: Definisikan komposisi bahan dengan berat dan persentase
- **Production**: Buat produksi berdasarkan konsep resep dengan target weight
- **Snapshot & Scaling**: Generate item produksi otomatis scaling dari konsep
- **Golongan**: Grouping item tambahan (global)
- **Tab (Split Batch)**: Bagi produksi menjadi beberapa batch
- **Dosis Kalkulator**: Hitung dosis otomatis berdasarkan target produksi
- **PDF Export**: Cetak laporan produksi

## Teknologi

- **Backend**: Laravel 12, PHP 8.4+
- **Frontend**: Blade, CSS, Vanilla JS
- **Database**: SQLite / MySQL
- **PDF**: DomPDF

## Instalasi

```bash
git clone https://github.com/10969sosho/bu-vania-formula.git
cd bu-vania-formula
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Struktur Branches

- `main` - Branch production (stable)
- `develop` - Branch pengembangan
- `revisi/*` - Branch per revisi fitur

## Lisensi

Hak cipta milik BU VANIA.
