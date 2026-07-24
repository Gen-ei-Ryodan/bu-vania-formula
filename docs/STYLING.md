# Styling Guide — Program Formula Dashboard

## 1. Font

| Properti | Value |
|----------|-------|
| Font Utama | **Inter** (Google Fonts) |
| Fallback | `system-ui, -apple-system, sans-serif` |
| Base Size | `14px` |
| Line Height | `1.5` |

```css
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
--font: 'Inter', system-ui, -apple-system, sans-serif;
```

---

## 2. Warna

### 2.1 Color Tokens (CSS Variables)

```css
--bg: #F6F8FB;               /* Background halaman */
--sidebar: #0F172A;           /* Background sidebar (dark navy) */
--sidebar-secondary: #1E293B; /* Sidebar hover */
--sidebar-text: #94A3B8;      /* Teks sidebar */
--sidebar-text-active: #F8FAFC; /* Teks sidebar aktif */
--card: #FFFFFF;              /* Background card / form */
--card-alt: #F8FAFC;          /* Alternatif card (striped row) */
--border: #E2E8F0;            /* Border utama */
--border-light: #F1F5F9;      /* Border tipis (bottom row) */
```

### 2.2 Brand Colors

```css
--primary: #2563EB;            /* Biru utama (button, link, active) */
--primary-hover: #1D4ED8;      /* Biru hover */
--primary-light: rgba(37,99,235,0.08);  /* Biru transparan (badge/bg) */
--primary-border: rgba(37,99,235,0.25); /* Biru border transparan */
```

### 2.3 Semantic Colors

```css
--success: #16A34A;            /* Hijau (success badge, alert) */
--success-light: rgba(22,163,74,0.08);
--warning: #F59E0B;            /* Kuning */
--warning-light: rgba(245,158,11,0.08);
--danger: #DC2626;             /* Merah (danger button, badge, alert) */
--danger-light: rgba(220,38,38,0.08);
--info: #0891B2;               /* Cyan */
```

### 2.4 Text Colors

```css
--text: #0F172A;               /* Teks utama (heading, body) */
--text-secondary: #64748B;     /* Teks sekunder (label, deskripsi) */
--text-muted: #94A3B8;         /* Teks redup (placeholder, muted cell) */
```

### 2.5 Dark Mode

```css
.dark {
  --bg: #020617;
  --card: #0F172A;
  --card-alt: #1E293B;
  --border: #1E293B;
  --text: #F8FAFC;
  --text-secondary: #94A3B8;
  --text-muted: #64748B;
}
```

Toggle dark mode via Alpine.js: `x-data="{ dark: false }" :class="dark ? 'dark' : ''"`

---

## 3. Layout

### 3.1 Layout Variables

```css
--gap: 24px;           /* Jarak antar section */
--pad: 32px;           /* Padding halaman (desktop) */
--pad-sm: 16px;        /* Padding halaman (mobile) */
--section-gap: 24px;   /* Jarak antar content section */
--sidebar-w: 260px;    /* Lebar sidebar */
--topbar-h: 72px;      /* Tinggi topbar */
--content-max: 1600px; /* Maksimal lebar konten */
```

### 3.2 Struktur Layout

```
.layout                    (flex, min-height: 100vh)
├── .sidebar               (fixed, left:0, width: 260px, height: 100vh)
│   ├── .sidebar-brand     (logo + nama aplikasi)
│   ├── .sidebar-nav       (navigasi)
│   │   ├── .nav-section   (label grup: "Master", "Proses", "Laporan")
│   │   └── .nav-link      (item menu)
│   └── .sidebar-footer    (user info)
└── .main                  (margin-left: 260px)
    ├── .topbar            (sticky, height: 72px, blur background)
    └── .page              (padding: 32px, max-width: 1600px, centered)
```

---

## 4. Cards

### 4.1 Default Card (`.card`)

```css
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;         /* --radius-card */
  box-shadow: var(--shadow);   /* 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.04) */
}
.card-header { padding: 20px 24px; border-bottom: 1px solid var(--border); }
.card-body   { padding: 24px; }
.card-body-sm { padding: 16px 24px; }
```

### 4.2 Stat Card (Dashboard Home)

```css
.stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
.stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 20px; }
.stat-card-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; margin-bottom: 12px; }
.stat-card .label { font-size: 12px; color: var(--text-secondary); font-weight: 500; }
.stat-card .value { font-size: 22px; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
```

### 4.3 Detail Card (Production Show)

```css
.detail-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
.detail-card-header { display: flex; align-items: center; gap: 8px; padding: 18px 20px; border-bottom: 1px solid var(--border); font-size: 14px; font-weight: 600; }
.detail-card-body { padding: 20px; }
```

### 4.4 Stat Card Modern (Production Show)

```css
.stats-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
.stat-card-modern { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 20px; }
.stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; margin-bottom: 12px; }
.stat-content .stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
.stat-content .stat-value { font-size: 22px; font-weight: 700; letter-spacing: -0.3px; }
```

### 4.5 Capacity Card (Circular Progress)

```css
.capacity-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; }
.circular-progress { width: 120px; height: 120px; border-radius: 50%; background: conic-gradient(var(--primary) calc(var(--pct)*3.6deg), #E2E8F0 calc(var(--pct)*3.6deg)); }
.cap-progress-fill { background: linear-gradient(90deg, var(--primary), #60A5FA); }
```

---

## 5. Tables

### 5.1 Standard Table (`.table-wrap` + `table.data`)

```css
.table-wrap { overflow-x: auto; border-radius: 16px; border: 1px solid var(--border); }
table.data { width: 100%; border-collapse: collapse; font-size: 13px; }
table.data thead { background: var(--card-alt); }
table.data thead th { padding: 12px 16px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); border-bottom: 1px solid var(--border); white-space: nowrap; position: sticky; top: 0; z-index: 2; }
table.data tbody td { padding: 12px 16px; border-bottom: 1px solid var(--border-light); vertical-align: middle; color: var(--text); }
table.data tbody tr:nth-child(even) { background: rgba(248,250,252,0.5); }
table.data tbody tr:hover { background: var(--card-alt); }
```

### 5.2 Modern Table (`.table-modern`)

```css
.table-modern { width: 100%; border-collapse: collapse; font-size: 13px; }
.table-modern thead th { background: var(--card-alt); padding: 10px 14px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary); border-bottom: 1px solid var(--border); white-space: nowrap; }
.table-modern tbody td { padding: 10px 14px; border-bottom: 1px solid var(--border-light); vertical-align: middle; }
.table-modern tbody tr:nth-child(even) { background: rgba(248,250,252,0.4); }
```

### 5.3 Cell Helpers

```css
.cell-muted { color: var(--text-secondary); }
.cell-actions { white-space: nowrap; text-align: right; }
.cell-number { font-weight: 600; color: var(--text-secondary); }
```

---

## 6. Forms

### 6.1 Form Card (`.form-card`)

```css
.form-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; box-shadow: var(--shadow); }
.form-card-header { padding: 20px 24px; border-bottom: 1px solid var(--border); }
.form-card-body { padding: 24px; }
.form-card-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; gap: 8px; justify-content: flex-end; }
```

### 6.2 Form Grid

```css
.form-grid   { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.form-grid-full { grid-column: 1 / -1; }
```

### 6.3 Field

```css
.field { display: flex; flex-direction: column; gap: 6px; }
.field .label { font-size: 12px; font-weight: 600; color: var(--text); letter-spacing: 0.01em; }
.field .label-optional { font-weight: 400; color: var(--text-muted); font-size: 11px; }
```

### 6.4 Input Styling

```css
input[type='text'], input[type='number'], input[type='date'], select, textarea {
  width: 100%;
  height: 48px;
  padding: 0 16px;
  border-radius: 12px;          /* --radius-input */
  border: 1px solid var(--border);
  background: var(--card);
  color: var(--text);
  font-size: 14px;
  font-family: var(--font);
  outline: none;
  transition: border-color .15s ease, box-shadow .15s ease;
}
input:focus, select:focus, textarea:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
}
textarea { min-height: 100px; padding: 12px 16px; resize: vertical; }
```

### 6.5 Input Group (Number + Unit)

```css
.input-group { display: grid; grid-template-columns: 1fr 110px; gap: 8px; }
```

### 6.6 Select Custom Arrow

Select memiliki custom dropdown arrow via SVG inline background-image.

### 6.7 Checkbox Custom

Checkbox dikustom dengan SVG checkmark. Ukuran: `20px x 20px`, border-radius: `6px`.

### 6.8 Inline Input (Small Forms)

```css
.inline-input { height: 36px; padding: 0 10px; border-radius: 8px; border: 1px solid var(--border); font-size: 12px; }
```

---

## 7. Buttons

### 7.1 Base Button (`.btn`)

```css
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  height: 42px;
  padding: 0 18px;
  border-radius: 12px;           /* --radius-button */
  border: 1px solid var(--border);
  background: var(--card);
  color: var(--text);
  font-size: 13px;
  font-weight: 600;
  font-family: var(--font);
  cursor: pointer;
  white-space: nowrap;
  text-decoration: none;
  line-height: 1;
}
```

### 7.2 Button Variants

| Class | Style |
|-------|-------|
| `.btn-primary` | `--primary` background, white text |
| `.btn-danger` | Red tint (`.danger-light` bg, `--danger` border) |
| `.btn-success` | Green tint (`.success-light` bg, `--success` border) |
| `.btn-ghost` | Transparent bg, no border |
| `.btn-sm` | Height: 34px, padding: 0 14px, font-size: 12px |
| `.btn-lg` | Height: 48px, padding: 0 24px, font-size: 14px |
| `.btn-icon` | Width: 42px, centered icon |

### 7.3 Hover & Active

```css
.btn:hover { background: var(--card-alt); }
.btn:active { transform: scale(.98); }
```

---

## 8. Badges

```css
.badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; line-height: 1.2; }
.badge-primary { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }
.badge-success { background: var(--success-light); color: var(--success); }
.badge-warning { background: var(--warning-light); color: #B45309; }
.badge-danger  { background: var(--danger-light); color: var(--danger); }
.badge-muted   { background: var(--card-alt); color: var(--text-secondary); border: 1px solid var(--border); }
```

### 8.1 Chip Badge (Production Show)

```css
.chip-badge { padding: 3px 10px; border-radius: 999px; background: var(--card); color: var(--text-secondary); font-size: 11px; font-weight: 600; border: 1px solid var(--border); }
.chip-primary { background: var(--primary-light); color: var(--primary); border-color: var(--primary-border); }
.source-badge { padding: 3px 8px; border-radius: 999px; background: #EEF2FF; color: #4F46E5; font-size: 11px; font-weight: 600; }
.meta-chip { padding: 3px 10px; border-radius: 6px; background: var(--card-alt); color: var(--text-secondary); font-size: 12px; font-weight: 500; border: 1px solid var(--border); }
```

---

## 9. Alerts

```css
.alert { display: flex; align-items: flex-start; gap: 10px; padding: 14px 18px; border-radius: 16px; border: 1px solid var(--border); font-size: 13px; }
.alert-success { border-color: rgba(22,163,74,0.25); background: var(--success-light); color: #166534; }
.alert-danger  { border-color: rgba(220,38,38,0.25); background: var(--danger-light); color: #991B1B; }
.alert-icon { width: 20px; height: 20px; flex-shrink: 0; margin-top: 1px; }
```

Dipakai di layout untuk `session('ok')`, `session('error')`, dan `$errors->all()`.

---

## 10. Page Structure

### 10.1 Page Hero

```css
.page-hero { margin-bottom: 24px; }
.page-hero h1 { font-size: 28px; font-weight: 700; letter-spacing: -0.5px; color: var(--text); margin: 0; }
.page-hero p { font-size: 14px; color: var(--text-secondary); margin: 6px 0 0; }
.page-hero-actions { display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap; }
```

### 10.2 Detail Hero (Production Show)

```css
.detail-hero-title { font-size: 28px; font-weight: 700; letter-spacing: -0.5px; }
.status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; }
.status-active { background: rgba(22,163,74,0.1); color: #166534; border: 1px solid rgba(22,163,74,0.2); }
.status-inactive { background: rgba(220,38,38,0.1); color: #991B1B; border: 1px solid rgba(220,38,38,0.2); }
.status-dot { width: 7px; height: 7px; border-radius: 50%; }
```

### 10.3 Content Section

```css
.content-section { margin-bottom: 24px; }
.content-section:last-child { margin-bottom: 0; }
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
.section-header h2 { font-size: 18px; font-weight: 600; letter-spacing: -0.3px; }
```

### 10.4 Breadcrumb

```css
.topbar-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-secondary); }
.detail-hero-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-muted); }
.detail-hero-breadcrumb a { color: var(--primary); }
```

---

## 11. Modals

```css
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 24px; }
.modal { background: var(--card); border-radius: 16px; max-width: 500px; width: 100%; box-shadow: var(--shadow-lg); overflow: hidden; }
.modal-header { display: flex; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid var(--border); }
.modal-header h3 { font-size: 16px; font-weight: 600; margin: 0; }
.modal-body { padding: 24px; }
.modal-footer { display: flex; justify-content: flex-end; gap: 8px; padding: 16px 24px; border-top: 1px solid var(--border); }
```

---

## 12. Empty State

```css
.empty-state { text-align: center; padding: 48px 24px; color: var(--text-secondary); }
.empty-state-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--card-alt); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; font-size: 24px; }
.empty-state h3 { font-size: 16px; font-weight: 600; margin: 0 0 6px; }
.empty-state p { font-size: 13px; margin: 0 0 20px; }
.empty-mini { text-align: center; padding: 16px; color: var(--text-muted); font-size: 13px; }
```

---

## 13. Toolbar (Filter/Search Bar)

```css
.toolbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
.toolbar .field { min-width: 200px; }
.toolbar-actions { display: flex; gap: 8px; align-items: center; margin-left: auto; }
```

---

## 14. Grid Helpers

```css
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.stack  { display: flex; flex-direction: column; gap: 12px; }
.inline { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
.right  { margin-left: auto; }
.muted  { color: var(--text-secondary); }
```

### 14.1 Info Grid

```css
.info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; }
.info-item .label { font-size: 11px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }
.info-item .value { font-size: 14px; font-weight: 600; color: var(--text); }
```

### 14.2 Info Rows (Production Show)

```css
.info-rows { display: grid; gap: 0; }
.info-row { display: flex; align-items: flex-start; gap: 12px; padding: 8px 0; }
.info-row-icon { width: 28px; height: 28px; border-radius: 8px; background: var(--card-alt); display: flex; align-items: center; justify-content: center; }
.info-row-label { font-size: 11px; color: var(--text-muted); }
.info-row-value { font-size: 14px; font-weight: 600; color: var(--text); }
.info-divider { height: 1px; background: var(--border-light); }
```

### 14.3 Detail Grid 2 Columns

```css
.detail-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
```

### 14.4 Concept Form Grid

```css
.grid-form-row { display: grid; grid-template-columns: 2fr 1fr 1fr 0.75fr 0.5fr; gap: 12px; align-items: end; }
.grid-item-row { display: grid; grid-template-columns: 2fr 1.2fr 0.8fr auto auto; gap: 12px; align-items: end; }
```

---

## 15. Laporan Sore Specific

### 15.1 Section Color Indicators

```css
.section-sisa_kemarin .card-header { border-left: 4px solid var(--warning); }
.section-campuran_hari_ini .card-header { border-left: 4px solid var(--primary); }
.section-kirim_hari_ini .card-header { border-left: 4px solid var(--success); }
.section-stock .card-header { border-left: 4px solid #8B5CF6; }  /* Purple */
```

### 15.2 Form Row Konsep

```css
.form-row-konsep { display: flex; gap: 8px; align-items: end; flex-wrap: wrap; padding: 12px 16px; background: var(--card-alt); border: 1px solid var(--border); border-radius: 16px; }
```

### 15.3 Summary Box

```css
.summary-box { background: var(--primary-light); border: 1px solid var(--primary-border); border-radius: 16px; padding: 20px; display: flex; gap: 24px; flex-wrap: wrap; }
.summary-item .label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
.summary-item .value { font-size: 20px; font-weight: 700; color: var(--text); }
```

---

## 16. Panel (Laporan Sore Form)

```css
.panel { background: var(--card); border: 1px solid var(--border); border-radius: 16px; box-shadow: var(--shadow); margin-bottom: 24px; }
.panel-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--border); }
.panel-body { padding: 24px; }
```

---

## 17. Progress Bar

```css
.progress { height: 8px; background: var(--card-alt); border-radius: 999px; overflow: hidden; }
.progress-bar { height: 100%; border-radius: 999px; background: var(--primary); }
.progress-bar.success { background: var(--success); }
.progress-bar.warning { background: var(--warning); }
.progress-bar.danger { background: var(--danger); }
```

---

## 18. Mode Toggle (Golongan / Tab)

```css
.mode-radio input { display: none; }
.mode-radio-label { display: flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 10px; border: 1px solid var(--border); font-size: 13px; font-weight: 500; color: var(--text-secondary); cursor: pointer; }
.mode-radio input:checked + .mode-radio-label { background: var(--primary); border-color: var(--primary); color: #fff; }
```

---

## 19. Group Card (Golongan / Tab Items)

```css
.group-card { background: var(--card-alt); border: 1px solid var(--border); border-radius: 12px; padding: 16px; margin-bottom: 12px; }
.group-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.group-name { font-size: 15px; font-weight: 700; color: var(--text); }
.group-item-form { display: flex; gap: 8px; align-items: end; flex-wrap: wrap; margin-bottom: 10px; }
```

---

## 20. Shadows

```css
--shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.04);
--shadow-lg: 0 4px 12px rgba(0,0,0,0.06), 0 16px 32px rgba(0,0,0,0.06);
```

Dark mode shadows:
```css
.dark {
  --shadow: 0 1px 3px rgba(0,0,0,0.2), 0 8px 24px rgba(0,0,0,0.2);
}
```

---

## 21. Border Radii

```css
--radius-card: 16px;     /* Card, Modal, Panel, Alert, Form Card */
--radius-input: 12px;    /* Input, Select, Textarea */
--radius-button: 12px;   /* Button */
--radius-badge: 999px;   /* Badge, Chip */
```

---

## 22. Sidebar Styling

```css
.sidebar { width: 260px; background: #0F172A; z-index: 100; }
.sidebar-brand { padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.06); }
.sidebar-brand-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--primary); color: #fff; font-weight: 700; }
.nav-section { padding: 20px 12px 4px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(148,163,184,0.5); }
.nav-link { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 10px; color: var(--sidebar-text); font-size: 13px; font-weight: 500; }
.nav-link.active { background: var(--primary); color: #fff; font-weight: 600; }
.nav-link:hover { background: var(--sidebar-hover); color: var(--sidebar-text-active); }
```

---

## 23. Topbar

```css
.topbar { position: sticky; top: 0; z-index: 50; height: 72px; background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 0 32px; }
.dark .topbar { background: rgba(15,23,42,0.8); }
.topbar-user { display: flex; align-items: center; gap: 10px; padding: 6px 10px 6px 6px; border-radius: 10px; }
.topbar-avatar { width: 32px; height: 32px; border-radius: 8px; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; }
.topbar-breadcrumb { font-size: 13px; color: var(--text-secondary); }
```

---

## 24. Responsive Breakpoints

### Desktop (> 1024px)
- Sidebar visible, layout normal
- 5-column stats, 2-column detail grid

### Tablet / Small Desktop (≤ 1024px)
- Sidebar hidden, toggle via burger button
- `.burger` button appears
- `.grid-4` becomes 2 columns
- Stats: 3 columns

### Mobile (≤ 768px)
- `.topbar` padding: 0 16px, height: 64px
- `.page` padding: 16px
- `.form-grid`, `.grid-2/3/4` → 1 column
- Stats: 2 columns
- Heading h1: 22px
- `.topbar-user-info` hidden
- `.toolbar` stacked vertically
- `.panel-header` stacked

### Small Mobile (≤ 480px)
- Stats: 1 column
- Info grid: 2 columns
- Breadcrumb truncated (hides middle items)

---

## 25. Utility Classes

| Class | Purpose |
|-------|---------|
| `.hidden` | `display: none !important` |
| `.muted` | Color: `var(--text-secondary)` |
| `.right` | `margin-left: auto` |
| `.inline` | `display: flex; gap: 8px; align-items: center` |
| `.stack` | `display: flex; flex-direction: column; gap: 12px` |
| `.divider` | `height: 1px; background: var(--border)` |

---

## 26. Dosis Calculator (Production Show)

Modal khusus untuk kalkulasi dosis dengan form fields:
- Item (readonly)
- Kapasitas (readonly)
- Berat Dosis (input number)
- Satuan (select with conversion)
- Per (input number, default 1)
- Satuan Per (select with conversion)

Formula: `((berat_dosis * konversi_satuan) / (per * konversi_satuan_per)) * kapasitas_target`

---

## 27. SVG Icons

Semua icon menggunakan inline SVG dari Lucide Icons (stroke-based, `stroke-width="2"`, `stroke-linecap="round"`, `stroke-linejoin="round"`). Ukuran default: `width="16" height="16"`, di button/icon-wrapper: `width="20" height="20"`.

Tidak ada icon font — semua icon inline SVG via Blade template.

---

## 28. Dependencies & CDN

```html
<!-- Alpine.js 3.x untuk interaktivitas -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- CSS Design System -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<!-- Custom JS -->
<script src="{{ asset('js/app.js') }}" defer></script>
```
