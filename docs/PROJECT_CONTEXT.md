# Project Context

## Nama Project

**Program Formula** — Sistem Produksi Pakan & Laporan Harian

## Tujuan Project

Aplikasi web untuk mengelola produksi pakan ternak secara end-to-end:
- Menyusun resep/konsep pakan dengan komposisi bahan baku
- Menjalankan produksi berdasarkan konsep dengan target berat tertentu
- Menghitung scaling bahan baku otomatis dari resep dasar
- Mengelola batch produksi (splitting) dan dosis obat/vitamin
- Mencatat laporan harian kandang (Laporan Sore) dengan 4 seksi

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend** | Laravel 12, PHP 8.4+ |
| **Frontend** | Blade templating, Alpine.js 3.x |
| **CSS** | Custom design system (CSS murni, tanpa framework) |
| **JavaScript** | Vanilla JS + Alpine.js |
| **Database** | SQLite (dev) / MySQL (production) |
| **PDF** | DomPDF (barryvdh/laravel-dompdf) |
| **Auth** | Session-based (Laravel built-in) |

## Modul Utama

### 1. Master Data
- **Units** — Satuan berat (kg, gram, ton, sak) dengan konversi ke kg
- **Categories** — Kategori item (bahan pokok, vitamin, obat)
- **Items** — Bahan baku produksi (Jagung, Beras, Tepung Ikan, Vitamin, dll)
- **Concepts** — Resep dasar pakan (komposisi item + persentase + berat)
- **Pembuats** — Pembuat konsep (orang/tim yang meracik resep)
- **Locations** — Lokasi pabrik/kandang
- **Cages** — Kandang per lokasi

### 2. Production (Produksi Biasa)
Produksi pakan reguler dengan fitur:
- Pilih konsep → input target berat → scaling otomatis
- **Snapshot**: Rekap item produksi hasil scaling
- **Golongan (Groups)**: Tambahan item (vitamin, obat) secara global
- **Tab (Split Batch)**: Bagi produksi jadi beberapa sub-batch
- **Dosis Calculator**: Input dosis dalam gram, auto-konversi ke kg
- **PDF Export**: Cetak laporan produksi

### 3. Treatment (Produksi Pengobatan)
Sama seperti produksi biasa, dengan field tambahan:
- `treatment_day` — Hari ke berapa pengobatan
- `treatment_time` — Waktu (pagi/siang/malam/full)
- `treatment_duration_days` — Lama pengobatan (hari)

### 4. Laporan Sore
Pencatatan harian kandang dengan 4 section:
- **Sisa Kemarin** — Stok dari hari sebelumnya (H-1)
- **Campuran Hari Ini** — Hasil campuran hari ini
- **Kirim Hari Ini** — Barang yang dikirim
- **Stock** — Stok akhir yang tersedia

Setiap section: banyak kandang → banyak detail konsep → multi-select item tambahan

### 5. Reports
Laporan PDF rekap produksi.

## User Role

| Role | Akses |
|------|-------|
| **Admin** | Semua fitur: master data, produksi, pengobatan, laporan, reports |
| **Gudang** | Terbatas: hanya Laporan Sore |

Middlewares:
- `auth` — Semua route terproteksi
- `role:admin` — Route master data, produksi, reports

## Flow Bisnis Ringkas

```
Master Data (Unit, Item, Concept)
        ↓
  Production / Treatment
        ↓
  Pilih Concept → Input Target Weight
        ↓
  Scaling Otomatis (Snapshot Items)
        ↓
  + Golongan (opsional)
  + Tab / Split Batch (opsional)
        ↓
  Cetak PDF / Laporkan

=== Laporan Sore (harian) ===
  Pilih Lokasi + Tanggal
        ↓
  Per Section (4 section)
    ↓
  Tambah Kandang → Detail Konsep → Item Tambahan → Jumlah + Satuan
```

## Struktur Folder

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/          # API endpoints (concept, production)
│   │   ├── Auth/         # LoginController
│   │   └── Dashboard/    # Web controllers untuk dashboard
│   └── Middleware/        # Role middleware
├── Models/               # Eloquent models
├── Providers/            # ServiceProvider
└── Services/             # ProductionSnapshotService, ProductionTabService
config/                   # Laravel config
database/
├── migrations/           # ~30 migration files
└── seeders/              # DatabaseSeeder, LaporanSoreSeeder
public/
├── css/app.css           # Full design system (~1200 lines)
└── js/app.js             # Custom JS
resources/views/
├── auth/                 # Login
├── components/layouts/   # Dashboard layout (sidebar, topbar)
└── dashboard/
    ├── concepts/
    ├── items/
    ├── locations/
    ├── produksi/         # Productions & Treatments
    ├── laporan-sore/     # Laporan Sore (index, create, show)
    └── reports/          # Report PDF
routes/
├── web.php               # Semua web routes
└── api.php               # API endpoints (unauthenticated)
```
