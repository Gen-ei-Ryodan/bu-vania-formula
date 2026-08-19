# Database Schema

## Entity Relationship Summary

```
users ────< laporan_sore ────< laporan_sore_details ────< laporan_sore_detail_items >──── items
  │                                                  │
  │                                                  └──── concepts
  │                                                  └──── cages ────< locations
  │
  └──── productions ────< production_items >──── items
          │        │          │
          │        │          └──── concepts
          │        │
          │        ├──── production_groups ────< production_group_items >──── items (via group)
          │        │                                                    └── units (via input_unit)
          │        │
          │        └──── production_tabs ────< production_tab_items >──── items (via tab)
          │                                                          └── units (via input_unit)
          │
          └──── concepts ────< concept_items >──── items
          │
          └──── units (via target weight conversion)

cages ────< locations
items ────< categories
items ────< units (via default_unit_id)
concepts ────< pembuats
```

## Tabel Detail

### users
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| name | string | |
| email | string unique | |
| password | string | hashed |
| role | string | `admin` atau `gudang` |
| remember_token | string nullable | |
| timestamps | | |

### locations
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| name | string | |
| timestamps | | |

### cages
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| location_id | bigint FK→locations | |
| name | string | |
| timestamps | | |

### units
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| name | string | kg, gram, ton, sak, liter, ml |
| dimension | string | `mass` atau `volume`; unit tidak boleh dicampur |
| conversion_to_kg | decimal | Faktor ke unit dasar dimensinya; untuk volume diperlakukan sebagai faktor ke liter |
| timestamps | | |

### categories
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| name | string | |
| timestamps | | |

### items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| name | string | |
| category_id | bigint FK→categories nullable | |
| default_unit_id | bigint FK→units nullable | |
| price | decimal(16,2) nullable | Harga dalam rupiah |
| price_unit_value | decimal(16,6) nullable | Jumlah unit yang dihargai |
| price_unit_id | bigint FK→units nullable | Unit harga; mis. kg atau gram |
| timestamps | | |

### pembuats
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| name | string | |
| timestamps | | |

### concepts
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| name | string | |
| pembuat_id | bigint FK→pembuats nullable | |
| base_weight_kg | decimal | Patokan berat resep |
| timestamps | | |

### concept_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| concept_id | bigint FK→concepts | |
| item_id | bigint FK→items | |
| percentage | decimal(6,4) | Persentase komposisi |
| weight_kg | decimal(10,4) | Berat dalam kg |
| weight_unit_id | bigint FK→units nullable | Unit input pemakaian untuk audit; `weight_kg` tetap nilai ternormalisasi |
| timestamps | | |

### productions
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| name | string | |
| location | string | Legacy field (teks bebas) |
| cage | string | Legacy field (teks bebas) |
| concept_id | bigint FK→concepts | |
| target_weight_kg | decimal | Target produksi dalam kg |
| production_type | string | `biasa` atau `treatment` |
| is_active | boolean | Status aktif/nonaktif |
| start_date | date nullable | |
| duration_days | int nullable | |
| is_forever | boolean | Jika true, durasi tidak terbatas |
| mix_date | date nullable | Tanggal campur |
| treatment_day | int nullable | (Treatment) Hari ke- |
| treatment_time | string nullable | (Treatment) pagi/siang/malam/full |
| treatment_duration_days | int nullable | (Treatment) Lama pengobatan |
| notes | text nullable | |
| timestamps | | |

### production_items (Snapshot)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| production_id | bigint FK→productions | |
| item_id | bigint FK→items | |
| weight_kg | decimal(10,4) | Hasil scaling |
| percentage | decimal(6,4) nullable | |
| source | string nullable | Dari konsep mana |
| timestamps | | |

### production_groups
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| production_id | bigint FK→productions | |
| name | string | Nama golongan |
| timestamps | | |

### production_group_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| group_id | bigint FK→production_groups | |
| item_id | bigint FK→items | |
| weight_kg | decimal(10,4) | Berat dalam kg |
| is_dosis | boolean | Jika true, input dalam gram |
| weight_input_value | decimal | Nilai input (dalam unit user) |
| weight_input_unit_id | bigint FK→units | Unit input user |
| timestamps | | |

### production_tabs
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| production_id | bigint FK→productions | |
| name | string | Nama tab/batch |
| input_weight_kg | decimal | Berat input tab |
| remaining_weight_kg | decimal | Sisa berat avail untuk tab |
| timestamps | | |

### production_tab_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| tab_id | bigint FK→production_tabs | |
| item_id | bigint FK→items | |
| weight_kg | decimal(10,4) | |
| is_dosis | boolean | |
| weight_input_value | decimal | |
| weight_input_unit_id | bigint FK→units | |
| timestamps | | |

### laporan_sore
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| location_id | bigint FK→locations | |
| tanggal | date | Tanggal laporan |
| user_id | bigint FK→users | Pembuat laporan |
| timestamps | | |

### laporan_sore_details
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| laporan_sore_id | bigint FK→laporan_sore | |
| section | string | `sisa_kemarin`, `campuran_hari_ini`, `kirim_hari_ini`, `stock` |
| cage_id | bigint FK→cages nullable | |
| nama_tali | string nullable | |
| konsep_id | bigint FK→concepts | |
| jumlah | decimal | |
| satuan | string | |
| timestamps | | |

### laporan_sore_detail_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint AI PK | |
| laporan_sore_detail_id | bigint FK→laporan_sore_details | |
| item_id | bigint FK→items | |
| timestamps | | |

## Key Relationships

| Relationship | Type | FK Column |
|-------------|------|-----------|
| Location → Cage | 1:N | `cages.location_id` |
| Concept → ConceptItem | 1:N | `concept_items.concept_id` |
| ConceptItem → Item | N:1 | `concept_items.item_id` |
| Production → Concept | N:1 | `productions.concept_id` |
| Production → ProductionItem | 1:N | `production_items.production_id` |
| Production → ProductionGroup | 1:N | `production_groups.production_id` |
| Production → ProductionTab | 1:N | `production_tabs.production_id` |
| ProductionGroup → ProductionGroupItem | 1:N | `production_group_items.group_id` |
| ProductionTab → ProductionTabItem | 1:N | `production_tab_items.tab_id` |
| LaporanSore → LaporanSoreDetail | 1:N | `laporan_sore_details.laporan_sore_id` |
| LaporanSoreDetail → LaporanSoreDetailItem | 1:N | `laporan_sore_detail_items.laporan_sore_detail_id` |
| LaporanSoreDetail → Cage | N:1 | `laporan_sore_details.cage_id` |
| LaporanSoreDetail → Concept | N:1 | `laporan_sore_details.konsep_id` (konsep_id) |
| Item → Category | N:1 | `items.category_id` |
| Item → Unit (default) | N:1 | `items.default_unit_id` |
| Item → Unit (harga) | N:1 | `items.price_unit_id` |
| ConceptItem → Unit (pemakaian) | N:1 | `concept_items.weight_unit_id` |
| User → LaporanSore | 1:N | `laporan_sore.user_id` |
