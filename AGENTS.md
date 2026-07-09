# AGENTS.md — SOP for AI Agents

## Cara Membaca Dokumentasi

Sebelum melakukan tugas apa pun, baca dokumentasi dalam urutan berikut:

1. **PROJECT_CONTEXT.md** — Pahami project secara umum (nama, tujuan, modul)
2. **ARCHITECTURE.md** — Pahami pola coding dan struktur
3. **BUSINESS_RULES.md** — Pahami aturan bisnis (WAJIB, ini yang paling penting)
4. **CODING_STANDARDS.md** — Pahami standar coding yang harus diikuti
5. **DATABASE.md** — Pahami schema database
6. **API_REFERENCE.md** — Pahami endpoint yang tersedia
7. **CHANGELOG.md** — Pahami keputusan arsitektur yang sudah dibuat

## Aturan Kerja AI

### 1. Source of Truth
- Dokumentasi di `docs/*` adalah **single source of truth**
- Jika ada konflik antara dokumentasi dan kode, **tanyakan ke USER** — jangan memutuskan sendiri
- Jangan menulis kode yang melanggar BUSINESS_RULES.md

### 2. Tidak Perlu Buat Pola Baru
Ikuti pola yang sudah ada di ARCHITECTURE.md:
- Jangan buat Service Pattern baru jika tidak diperlukan
- Jangan buat Repository Pattern — project ini tidak menggunakan repository
- Jangan buat FormRequest — validasi inline di controller
- Jangan tambah package baru tanpa izin USER

### 3. Batasan Perubahan
- Jangan ubah struktur database tanpa diskusi dengan USER
- Jangan hapus migration yang sudah ada
- Jangan rename method/class yang sudah dipakai di view/route
- Jangan ubah CSS design system tanpa kebutuhan jelas
- Jangan tambah dependency baru tanpa konfirmasi

### 4. Workflow Analisa
Ketika USER memberi task:
1. Baca docs sesuai urutan di atas (jika belum dibaca di sesi ini)
2. Identifikasi modul mana yang terdampak
3. Cari kode terkait (model, controller, view, route)
4. Usul perubahan ke USER jika ada ketidakjelasan
5. Implementasi dengan mengikuti CODING_STANDARDS.md

### 5. Coding Constraints
- **PHP 8.4+**: Gunakan typed properties, match expression, named arguments jika sesuai
- **Laravel 12**: Ikuti Laravel best practices
- **Blade**: Gunakan component layout, jangan buat layout sendiri
- **CSS**: Edit `public/css/app.css` — jangan buat file CSS baru
- **JS**: Vanilla JS + Alpine.js — jangan gunakan jQuery, React, Vue, dll
- **Database**: SQLite-compatible — jangan gunakan MySQL-specific features
- **PDF**: Gunakan DomPDF — jangan ganti PDF library

### 6. Error Handling
- Jika ada error, analisis dulu sendiri sebelum tanya USER
- Cek log di `storage/logs/laravel.log`
- Jika blocking dan tidak ada solusi, jelaskan ke USER dengan opsi

### 7. Testing
- Project ini tidak memiliki automated tests
- Jangan buat unit/feature tests tanpa minta izin USER
- Pengujian manual via browser

### 8. Komunikasi
- Gunakan bahasa Indonesia untuk komunikasi dengan USER
- Selalu rujuk file dengan clickable path
- Jelaskan perubahan secara singkat, jangan bertele-tele
- Jika ragu, tanya — jangan tebak

### 9. Deployment Workflow

Project ini punya **2 remote**:
| Remote | URL |
|--------|-----|
| `origin` | `https://github.com/10969sosho/bu-vania-formula.git` |
| `gen-ei-ryodan` | `https://github.com/Gen-ei-Ryodan/bu-vania-formula.git` |

**Branch utama:**
- `develop` — Development branch (struktur folder normal Laravel)
- `deploy/shared-hosting` — Deployment ke shared hosting (struktur ada prefix `laravel/`)
- `main` — Production (jarang dipakai)

**Alur deploy ke shared hosting:**

1. **Push perubahan ke develop** — commit di branch `develop`, lalu:

   ```bash
   git push origin develop
   git push gen-ei-ryodan develop
   ```

2. **Sync ke deploy/shared-hosting** — pastikan perubahan juga ada di branch ini:

   ```bash
   git checkout deploy/shared-hosting
   git merge develop --no-ff
   # atau copy manual file dari app/... ke laravel/app/...
   git push origin deploy/shared-hosting
   git push gen-ei-ryodan deploy/shared-hosting
   ```

3. **Pull & deploy di server** (via SSH betw2231@juwana -p 65002):

   ```bash
   cd ~/repositories/bu-vania-formula
   git pull origin deploy/shared-hosting
   bash deploy.sh
   cd ~/public_html/formula.3putraperkasa.com/laravel
   php artisan migrate
   php artisan optimize:clear
   ```

**PENTING — Directory Structure di deploy/shared-hosting:**

Branch `deploy/shared-hosting` punya struktur folder berbeda:
- `laravel/app/` → setara dengan `app/` di develop
- `laravel/resources/views/` → setara dengan `resources/views/` di develop
- `laravel/database/migrations/` → setara dengan `database/migrations/` di develop

Saat merge `develop` ke `deploy/shared-hosting`, file mungkin tidak otomatis ter-sync ke path `laravel/`. **Selalu verifikasi** dengan:

```bash
grep -n "pembuat_id" laravel/app/Http/Controllers/Dashboard/ConceptController.php | wc -l
```

Jika jumlah baris berbeda dengan di `app/`, copy manual:

```bash
cp app/Http/Controllers/... laravel/app/Http/Controllers/...
cp resources/views/... laravel/resources/views/...
```

Kemudian commit ke `deploy/shared-hosting` dan push.
