# Architecture

## Pola Arsitektur

Laravel MVC standar dengan Blade templating (bukan SPA).

```
Browser ──HTTP──> web.php ──> Controller ──> Model ──> Database
                               │
                               └──> View (Blade)
```

## Frontend

| Aspek | Detail |
|-------|--------|
| **Templating** | Blade `@extends` / `@include` / components |
| **Layout** | Satu layout utama: `components/layouts/dashboard.blade.php` |
| **CSS** | Custom design system di `public/css/app.css` (~1200 baris) |
| **JS** | Alpine.js 3.x (CDN) + vanilla JS di `public/js/app.js` |
| **State** | Alpine.js `x-data` untuk UI state (sidebar toggle, dark mode) |
| **Form** | Form submission via `<form>` POST biasa, beberapa via Fetch API |
| **Styling** | CSS variables (design tokens), BEM-like naming, utility classes |

### CSS Design System
- File tunggal `public/css/app.css`
- CSS variables untuk warna, spacing, font
- Dark mode via `html.dark` class selector
- Komponen: `.card`, `.panel`, `.btn`, `.field`, `.table-wrap`, `.data`, `.badge`, `.alert`

## Backend

| Aspek | Detail |
|-------|--------|
| **Controller Pattern** | Resource controllers dengan explicit route binding |
| **Service Layer** | `ProductionSnapshotService` — generate snapshot items after store/update |
| | `ProductionTabService` — create tab with weight allocation |
| **Validation** | `$request->validate()` di controller method |
| **Transactions** | `DB::transaction()` untuk operasi multi-tabel |
| **Roles** | Gate-like via custom `role` middleware (check `User->role`) |
| **Auth** | Session-based (Laravel built-in auth) |

### Controller Structure
```
Controllers/
├── Api/              # Unauthenticated JSON API (External)
│   ├── ConceptController
│   ├── ProductionController
│   ├── ProductionGroupController
│   ├── ProductionGroupItemController
│   ├── ProductionTabController
│   └── ProductionTabItemController
│
├── Auth/             # Login
│   └── LoginController
│
└── Dashboard/        # Semua web routes (authenticated)
    ├── DashboardController       # Home / ringkasan
    ├── UnitController            # CRUD satuan
    ├── CategoryController        # CRUD kategori
    ├── ItemController            # CRUD item
    ├── PembuatController         # CRUD pembuat konsep
    ├── ConceptController         # CRUD konsep/resep
    ├── LocationController        # CRUD lokasi + cages (inline)
    ├── ProductionController      # CRUD produksi biasa + groups + tabs
    ├── TreatmentProductionController  # CRUD produksi pengobatan
    ├── LaporanSoreController     # CRUD laporan sore
    └── ReportController          # Laporan PDF
```

### Service Classes
- `App\Services\ProductionSnapshotService` — `generate(production)` dan `regenerate(production)`: membuat/memperbarui ProductionItem records berdasarkan scaling dari ConceptItem + target_weight_kg
- `App\Services\ProductionTabService` — `createTab(production, name, inputKg)`: membuat tab baru dan menghitung `remaining_weight_kg`

## Database

- **Database**: SQLite (development) / MySQL (production) — via `.env` config
- **ORM**: Eloquent ORM penuh
- **Migrations**: ~30 file, semua di `database/migrations/`
- **Seeders**: `DatabaseSeeder` (units, items, concepts, productions dummy) + `LaporanSoreSeeder`
- **Convention**: Timestamps `created_at`/`updated_at` aktif untuk semua tabel

### Naming Convention Database
- Tabel: `snake_case` plural
- Pivot: `singular_singular` (e.g., `concept_items`, `production_tab_items`)
- FK: `model_name_id` (e.g., `concept_id`, `location_id`)
- Kolom berat: `weight_kg` (disimpan dalam kilogram sebagai float)

## Authentication

- Session-based login
- Login page ada di `auth/login.blade.php`
- Logout via POST `/logout`
- Middleware `auth` untuk semua route
- Custom middleware `role:admin` untuk route admin-only
- User model memiliki helpers: `isAdmin()`, `isGudang()`

## Route Structure

### Web Routes (`routes/web.php`)
- `/login` — Login form (GET/POST)
- `/logout` — Logout (POST)
- `/dashboard` — Home dashboard (GET)
- `/laporan-sore` — Laporan Sore CRUD (resource, except edit/update)
- **Admin-only**: `/units`, `/categories`, `/pembuats`, `/items`, `/concepts`, `/locations`, `/productions`, `/treatments`, `/reports`

### API Routes (`routes/api.php`)
- Unauthenticated (external/3rd party)
- CRUD operations: concepts, productions, groups, tabs
- Digunakan untuk integrasi dari sistem eksternal

## PDF Generation

- Package: `barryvdh/laravel-dompdf`
- View-based: load Blade view → render as PDF
- Format: A4 portrait, custom inline CSS
- Files:
  - `dashboard/productions/pdf.blade.php`
  - `dashboard/treatments/pdf.blade.php`
  - `dashboard/reports/pdf.blade.php`
  - `dashboard/reports/concept_report_pdf.blade.php`

## Security

- Semua route web protected by `auth` middleware
- Admin-only route group menggunakan `role:admin` middleware
- CSRF protection di semua form (Laravel default)
- Mass assignment protection via `$fillable` di model
- Validation di semua input
- No API authentication (API routes publik — untuk integrasi internal)
