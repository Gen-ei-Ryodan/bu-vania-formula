# Coding Standards

## General
- **Bahasa**: PHP (backend), HTML+Blade (frontend), JavaScript (frontend)
- **Framework**: Laravel 12 conventions
- **PHP Version**: 8.4+

## Backend Standards

### Naming Convention
| Item | Convention | Example |
|------|-----------|---------|
| **Classes** | PascalCase | `ProductionSnapshotService` |
| **Methods** | camelCase | `generate()`, `storeGroup()` |
| **Variables** | camelCase | `$targetWeightKg` |
| **Database Tables** | snake_case plural | `production_groups`, `laporan_sore_details` |
| **Database Columns** | snake_case | `conversion_to_kg`, `is_forever` |
| **Foreign Keys** | `singular_id` | `concept_id`, `location_id` |
| **Routes** | kebab-case | `/laporan-sore`, `/productions` |
| **Route Names** | dot-notation | `productions.index`, `laporan-sore.store` |
| **Views** | kebab-case | `laporan-sore/show.blade.php` |

### Controller Standards
- Keep controllers thin — maksimal ~500 baris per controller
- Resource controller pattern untuk CRUD
- Validasi di controller method (tidak buat FormRequest terpisah)
- Gunakan `DB::transaction()` untuk multi-tabel operation
- Return redirect dengan session flash: `->with('ok', '...')`

### Model Standards
- Gunakan `$fillable` (bukan `$guarded`) untuk mass-assignment protection
- `$casts` untuk type casting (date, boolean, decimal)
- Gunakan query scopes: `scopeBiasa()`, `scopeTreatment()`
- Relationships: `BelongsTo`, `HasMany`, `HasOne`

### Service Layer
- Service class dipanggil via dependency injection di controller method
- Service menerima model sebagai parameter
- Contoh: `ProductionSnapshotService::generate(Production $production)`

### Validation
- `$request->validate()` inline di controller
- Validation rules menggunakan array syntax
- Custom validation messages via `ValidationException` jika perlu

### Database Migrations
- Satu migration per perubahan
- Nama file: `YYYY_MM_DD_HHMMSS_deskripsi.php`
- Gunakan `Schema::create()` untuk tabel baru
- Gunakan `Schema::table()` untuk alter
- SQLite compatible (jangan gunakan MySQL-specific features tanpa fallback)

### Seeders
- Gunakan `firstOrCreate()` untuk idempotent seeding
- Check `Model::exists()` sebelum seeding data dummy
- Referensi model via `Model::where('name', ...)->first()` bukan hardcoded ID

## Frontend Standards

### CSS
- File tunggal di `public/css/app.css`
- CSS variables untuk design tokens
- BEM-like naming: `.block__element--modifier` (longgar, tidak strict)
- Komentar section dengan `/* ====== */`
- Tidak ada CSS framework eksternal (Tailwind/Bootstrap)
- Responsive via media queries di bagian bawah

### JavaScript
- Vanilla JS (tidak ada framework JS)
- Alpine.js 3.x untuk interaktivitas UI (sidebar, dark mode)
- Event listener via `addEventListener` (bukan inline onclick)
- Template cloning untuk dynamic rows

### Blade
- Gunakan `<x-layouts.dashboard>` component layout
- `@stack('scripts')` untuk page-specific JS
- `@push('scripts')` untuk menambahkan script dari child view
- Indentasi: 4 spasi

### Design System CSS Variables
```css
--primary: #2563EB;
--success: #059669;
--warning: #D97706;
--danger: #DC2626;
--text: #0F172A;
--text-secondary: #475569;
--text-muted: #64748B;
--bg: #F1F5F9;
--card: #FFFFFF;
--border: #E2E8F0;
--radius-sm: 6px;
--radius-card: 12px;
--sidebar-width: 260px;
```

## Commit & Branch

### Branch Naming
- `main` — Production
- `develop` — Development
- `revisi/*` — Per revisi fitur

## Dependency Management
- Composer for PHP dependencies
- NPM for frontend build (jika ada)
- Avoid adding unnecessary packages
