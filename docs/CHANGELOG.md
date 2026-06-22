# Changelog

Catatan keputusan dan perubahan penting selama pengembangan.

## v1.2 (2026-06-21) — Laporan Sore + Rebranding

### Added
- **Laporan Sore** — Modul pencatatan harian kandang dengan 4 section:
  - Sisa Kemarin (auto H-1 date), Campuran Hari Ini, Kirim Hari Ini, Stock
  - Multi-select item tambahan per detail konsep
  - Filter kandang by lokasi
- **Dashboard sidebar**: Menu "Laporan Sore" untuk semua role (admin & gudang)
- **LaporanSoreSeeder** — Data dummy untuk testing (2 lokasi, 21 detail records)
- **User role `gudang`** — Role terbatas hanya akses Laporan Sore
- **Panel CSS** — Design system baru untuk form laporan sore

### Changed
- **Rebranding**: "BU VANIA2" → "Program Formula" di semua tampilan
- **PDF info-grid**: Dari flex layout → tabel (4 kolom) untuk DOMPDF compatibility
- **Dashboard sidebar**: Brand name & icon update
- **Database**: Tabel baru `laporan_sore`, `laporan_sore_details`, `laporan_sore_detail_items`

### Architecture Decisions
- Laporan Sore tidak bisa di-edit (hanya create, show, delete)
- Item tambahan pakai table pivot (`laporan_sore_detail_items`)
- Sisa Kemarin date dihitung otomatis via JavaScript (H-1)
- Form submission via Fetch API JSON untuk handle nested data kompleks

---

## v1.1 (2026-06-04) — Kategori, Status Aktif, Multiple Locations

### Added
- **Categories** — Kategori item (bahan pokok, vitamin, obat)
- **`is_active` field** di produksi — Untuk menandai produksi aktif/nonaktif
- **Dashboard home** — Ringkasan produksi aktif + total
- **Treatments PDF** — Export PDF untuk produksi pengobatan
- **Edit Group/Tab Items** — PUT endpoint untuk update weight

### Changed
- Item sekarang bisa memiliki category
- Dashboard menampilkan produksi aktif

---

## v1.0 (2026-05-21) — Production System Stabil

### Added
- **Production Types**: `biasa` dan `treatment` di tabel `productions`
- **Treatment fields**: `treatment_day`, `treatment_time`, `treatment_duration_days`
- **Weight Input System**: Kolom `weight_input_value` dan `weight_input_unit_id` di group & tab items
- **Duplicate Production**: Deep copy dengan snapshot, groups, tabs

### Changed
- Konversi dari gram ke kilogram untuk sistem berat (migration: `convert_gram_to_kilogram_system`)
- `seed_name` → `location` + `cage` fields

### Fixed
- Weight calculation overflow pada scaling besar

---

## v0.9 (2026-04-28) — Core Production

### Added
- **Concepts (Resep)** — CRUD dengan komposisi item + persentase
- **Items** — Master bahan baku
- **Units** — Satuan dengan konversi ke kg
- **Productions** — Basic CRUD + snaphot scaling
- **Groups (Golongan)** — Item tambahan per produksi
- **Tabs (Split Batch)** — Batch splitting
- **ProductionSnapshotService** — Auto-scaling dari concept items
- **ProductionTabService** — Tab allocation
- **PDF Export** — Produksi biasa
- **Laporan PDF** — Report all productions
- **API Endpoints** — External integration (concept, production, group, tab)

### Architecture Decisions
- Menggunakan Eloquent ORM penuh
- Service layer untuk business logic (not in controller)
- SQLite untuk development
- File-based CSS (no framework)
- Session-based auth
- PDF via DomPDF
