# API Reference

Base URL: `/api`

Semua API endpoint bersifat **publik (unauthenticated)** — digunakan untuk integrasi dari sistem eksternal.

## Concepts

### POST /api/concepts
Buat konsep baru beserta item komposisinya.

**Request Body:**
```json
{
  "name": "Resep Baru",
  "base_weight_kg": 1000,
  "items": [
    { "item_id": 1, "percentage": 50, "weight_kg": 500 },
    { "item_id": 2, "percentage": 30, "weight_kg": 300 },
    { "item_id": 3, "percentage": 20, "weight_kg": 200 }
  ]
}
```

**Response:** Redirect ke dashboard concepts index.

---

## Productions

### POST /api/productions
Buat produksi baru.

**Request Body:**
```json
{
  "name": "Produksi April",
  "concept_id": 1,
  "target_weight_value": 500,
  "target_weight_unit_id": 1,
  "location": "Lokasi A",
  "cage": "Kandang 1",
  "start_date": "2025-04-01",
  "mix_date": "2025-04-01",
  "duration_days": 30,
  "production_type": "biasa"
}
```

**Response:** Redirect ke dashboard productions show.

---

### POST /api/productions/{production}/generate
Generate snapshot items untuk produksi yang sudah ada. Scaling dari concept items berdasarkan target_weight_kg.

**Response:** 200 + JSON success.

---

## Production Groups

### POST /api/productions/{production}/groups
Buat golongan baru dalam produksi.

**Request Body:**
```json
{
  "name": "Golongan A"
}
```

**Response:** Redirect ke dashboard productions show.

---

### POST /api/groups/{group}/items
Tambah item ke golongan.

**Request Body:**
```json
{
  "item_id": 5,
  "weight_value": 500,
  "weight_unit_id": 2,
  "is_dosis": true
}
```

**Response:** Redirect ke dashboard productions show.

---

## Production Tabs

### POST /api/productions/{production}/tabs
Buat tab/batch baru dalam produksi.

**Request Body:**
```json
{
  "name": "Tab 1",
  "input_weight_value": 200,
  "input_weight_unit_id": 1
}
```

**Response:** Redirect ke dashboard productions show.

---

### POST /api/tabs/{tab}/items
Tambah item ke tab.

**Request Body:**
```json
{
  "item_id": 1,
  "weight_value": 100,
  "weight_unit_id": 1,
  "is_dosis": false
}
```

**Response:** Redirect ke dashboard productions show.

---

## Web Routes (Non-API)

Semua web routes ada di `routes/web.php`. Berikut summary endpoint utama:

### Authentication

| Method | URI | Name | Middleware |
|--------|-----|------|------------|
| GET | `/login` | `login` | guest |
| POST | `/login` | `login` | guest |
| POST | `/logout` | `logout` | auth |

### Dashboard

| Method | URI | Name | Middleware |
|--------|-----|------|------------|
| GET | `/dashboard` | `dashboard` | auth |

### Master Data (Admin only)

| Resource | URI | Controller | Notes |
|----------|-----|-----------|-------|
| CRUD | `/units` | `UnitController` | except show |
| CRUD | `/categories` | `CategoryController` | index, store, update, destroy |
| CRUD | `/pembuats` | `PembuatController` | except show |
| CRUD | `/items` | `ItemController` | except show |
| CRUD | `/concepts` | `ConceptController` | full resource |
| CRUD | `/locations` | `LocationController` | index, create, store, edit, update, destroy |
| POST | `/locations/{location}/cages` | `LocationController@storeCage` | |
| PUT | `/cages/{cage}` | `LocationController@updateCage` | |
| DELETE | `/cages/{cage}` | `LocationController@destroyCage` | |

### Production (Admin only)

| Method | URI | Name |
|--------|-----|------|
| GET/POST | `/productions` | `productions.index/store` |
| GET/POST | `/productions/create` | `productions.create` |
| GET/PUT | `/productions/{production}` | `productions.show/update` |
| DELETE | `/productions/{production}` | `productions.destroy` |
| POST | `/productions/duplicate` | `productions.duplicate` |
| GET | `/productions/{production}/pdf` | `productions.pdf` |
| POST | `/productions/{production}/groups` | `productions.groups.store` |
| DELETE | `/groups/{group}` | `groups.destroy` |
| POST | `/groups/{group}/items` | `groups.items.store` |
| PUT | `/group-items/{groupItem}` | `groups.items.update` |
| POST | `/productions/{production}/tabs` | `productions.tabs.store` |
| DELETE | `/tabs/{tab}` | `tabs.destroy` |
| POST | `/tabs/{tab}/items` | `tabs.items.store` |
| PUT | `/tab-items/{tabItem}` | `tabs.items.update` |

### Treatment (Admin only)

| Method | URI | Name |
|--------|-----|------|
| GET/POST | `/treatments` | `treatments.index/store` |
| GET | `/treatments/{production}/pdf` | `treatments.pdf` |

Struktur route sama dengan produksi biasa, dengan prefix `/treatments`.

### Reports (Admin only)

| Method | URI | Name |
|--------|-----|------|
| GET | `/reports` | `reports.index` |
| GET | `/reports/pdf` | `reports.pdf` |

### Laporan Sore (All roles)

| Method | URI | Name |
|--------|-----|------|
| GET | `/laporan-sore` | `laporan-sore.index` |
| GET | `/laporan-sore/create` | `laporan-sore.create` |
| POST | `/laporan-sore` | `laporan-sore.store` |
| GET | `/laporan-sore/{laporanSore}` | `laporan-sore.show` |
| DELETE | `/laporan-sore/{laporanSore}` | `laporan-sore.destroy` |

### Internal API (Admin only, JSON)

| Method | URI | Name |
|--------|-----|------|
| GET | `/api/locations/{location}/cages` | `api.locations.cages` |
| GET | `/api/productions/{production}/data` | `api.productions.data` |
