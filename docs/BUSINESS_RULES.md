# Business Rules

## 1. Master Data Rules

### Unit
- Setiap unit memiliki `conversion_to_kg` untuk standarisasi berat
  - Contoh: 1 kg = 1, 1 gram = 0.001, 1 ton = 1000, 1 sak = 50
- Unit tidak bisa dihapus jika masih digunakan sebagai default unit item

### Item
- Item bisa memiliki `category_id` (optional) untuk grouping
- Item bisa memiliki `default_unit_id` (optional)
- Item memiliki harga positif (`price`) untuk jumlah positif (`price_unit_value`) pada `price_unit_id`.
- Harga resep tidak diinput manual; biaya item dihitung dari berat pemakaian setelah dikonversi ke kilogram.
- Item yang sudah digunakan di konsep, produksi, atau laporan tidak boleh dihapus

### Concept (Resep)
- Setiap konsep memiliki `base_weight_kg` sebagai patokan komposisi
- `ConceptItem`: item dalam konsep memiliki `percentage` (persentase) dan `weight_kg`
- Total persentase semua item dalam satu konsep harus = 100%
- Biaya item = `price / (price_unit_value × konversi price_unit ke kg) × weight_kg`; total harga resep adalah jumlah biaya seluruh item.
- Contoh: Rp10.000/kg untuk pemakaian 500 gram = Rp5.000; Rp500/100 gram untuk pemakaian 200 gram = Rp1.000.
- Satu konsep bisa digunakan di banyak produksi

### Location & Cage
- Location memiliki banyak Cage (1:N)
- Nama cage unique per location
- Cage bisa dipindahkan ke lokasi lain via edit

## 2. Production Rules

### Produksi Biasa vs Pengobatan
- `production_type = 'biasa'` — Produksi pakan reguler
- `production_type = 'treatment'` — Produksi pengobatan
- Kedua tipe ada di tabel `productions` yang sama, dibedakan via field `production_type`
- Treatment punya field tambahan: `treatment_day`, `treatment_time`, `treatment_duration_days`

### Scaling (Snapshot Generation)
- Ketika produksi dibuat/diupdate, sistem auto-generate `ProductionItem` records
- Formula: `weight_kg = (target_weight_kg / concept.base_weight_kg) * concept_item.weight_kg`
- Source column mencatat dari konsep mana item berasal

### Golongan (Groups)
- Golongan adalah item tambahan yang ditambahkan secara manual ke produksi
- Group items bisa berupa dosis (`is_dosis = true`) — misal vitamin/obat dalam gram
- Input weight: user input dalam unit apapun, sistem konversi ke kg
- Edit berat: bisa diubah via PUT endpoint

### Tab (Split Batch)
- Tab membagi produksi menjadi beberapa batch
- Setiap tab memiliki `input_weight_kg` dan `remaining_weight_kg`
- `remaining_weight_kg` dihitung otomatis oleh `ProductionTabService`
- Total semua tab tidak boleh melebihi target_weight_kg produksi
- Tab items bisa berupa dosis atau non-dosis

### Status Aktif
- Produksi memiliki field `is_active` (boolean)
- Produksi aktif muncul di dashboard home ("Produksi Aktif Saat Ini")
- Produksi bisa di-nonaktifkan tanpa dihapus

### Duplicate
- Produksi bisa di-duplicate (deep copy: snapshot, groups, tabs)
- Copy memiliki `is_active = true` secara default
- User diarahkan ke halaman edit copy setelah duplicate

## 3. Laporan Sore Rules

### Struktur Laporan
- Satu laporan memiliki tepat satu lokasi dan satu tanggal
- Laporan memiliki 4 section wajib:
  1. `sisa_kemarin` — Sisa Kemarin
  2. `campuran_hari_ini` — Campuran Hari Ini
  3. `kirim_hari_ini` — Kirim Hari Ini
  4. `stock` — Stock

### Section Rules
- Setiap section bisa memiliki 0 atau banyak kandang (cage)
- Setiap kandang bisa memiliki 0 atau banyak detail konsep
- Setiap detail konsep bisa memiliki 0 atau banyak item tambahan

### Tanggal
- Tanggal laporan dipilih manual oleh user
- Section "Sisa Kemarin" secara otomatis menampilkan tanggal H-1

### Data Entry
- Kandang: pilih dari master cage (filter by location)
- Nama Tali: text input manual
- Konsep: pilih dari master concept
- Item Tambahan: multi-select (bisa pilih >1 item per detail)
- Jumlah: numeric input
- Satuan: text input manual (tidak terikat master unit)

### Read-Only After Create
- Laporan sore tidak bisa diedit (hanya create, show, delete)
- Delete hanya via tombol hapus di halaman show

## 4. Access Rules

### Role: Admin
- Akses penuh ke semua menu sidebar
- Bisa CRUD semua master data
- Bisa membuat/mengelola produksi
- Bisa melihat laporan PDF
- Bisa mengelola laporan sore

### Role: Gudang
- Hanya bisa akses menu "Laporan Sore"
- Tidak bisa melihat menu master data, produksi, atau laporan PDF
- Bisa membuat laporan sore baru dan melihat yang sudah ada

### Menu Sidebar (per role)
**Admin:**
Dashboard → Master (Satuan, Kategori, Pembuat, Lokasi, Item, Konsep) → Proses (Produksi, Pengobatan) → Laporan → Operasional (Laporan Sore)

**Gudang:**
Dashboard → Operasional (Laporan Sore)

## 5. PDF Export Rules
- Setiap produksi bisa di-export PDF
- Format: A4, portrait, inline CSS styling
- PDF header: logo "PF" + company name "Program Formula"
- Tabel info grid: 4 kolom dengan border per sel

## 6. General Rules
- Semua timestamp `created_at`/`updated_at` diisi otomatis oleh Eloquent
- Soft delete TIDAK digunakan — data langsung dihapus
- Tidak ada audit trail / log aktivitas
- Tidak ada sistem notifikasi
- Tidak ada queue/job system
