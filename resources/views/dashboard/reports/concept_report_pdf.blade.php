<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} — Program Formula</title>
    <style>
        @page { margin: 18mm 14mm 22mm 14mm; size: A4 portrait; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8.5pt;
            color: #1E293B;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }
        .company-header {
            display: flex; align-items: center; gap: 12px;
            padding-bottom: 10px; border-bottom: 2px solid #2563EB;
            margin-bottom: 14px;
        }
        .company-logo {
            width: 48px; height: 48px; background: #2563EB;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 18px; flex-shrink: 0;
        }
        .company-info h1 { margin: 0; font-size: 14pt; font-weight: 700; color: #0F172A; }
        .company-info p { margin: 1px 0 0; font-size: 7.5pt; color: #64748B; }

        .report-title { text-align: center; margin-bottom: 14px; }
        .report-title h2 { margin: 0; font-size: 12pt; font-weight: 700; color: #0F172A; }
        .report-title .meta { font-size: 7pt; color: #94A3B8; margin-top: 2px; }

        .summary-grid {
            display: flex; gap: 8px; flex-wrap: wrap;
            margin-bottom: 14px; padding: 10px 12px;
            background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px;
        }
        .summary-item { flex: 1; min-width: 80px; }
        .summary-item .label { font-size: 6.5pt; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; }
        .summary-item .value { font-size: 10pt; font-weight: 700; color: #0F172A; }

        table {
            width: 100%; border-collapse: collapse; font-size: 7.5pt;
            page-break-inside: avoid;
        }
        thead th {
            background: #F1F5F9; color: #475569; font-weight: 600;
            font-size: 6.5pt; text-transform: uppercase; letter-spacing: 0.3px;
            padding: 5px 8px; text-align: left;
            border-bottom: 1.5px solid #CBD5E1;
        }
        tbody td {
            padding: 4px 8px; border-bottom: 0.5px solid #E2E8F0;
        }
        tbody tr:nth-child(even) { background: #F8FAFC; }
        tbody tr:last-child td { border-bottom: 1px solid #CBD5E1; }

        .section { margin-bottom: 14px; page-break-inside: avoid; }
        .section-header {
            background: #2563EB; color: #fff;
            padding: 5px 10px; font-size: 8.5pt; font-weight: 700;
            border-radius: 4px 4px 0 0; page-break-after: avoid;
        }

        .footer {
            position: fixed; bottom: -16mm; left: 0; right: 0;
            text-align: center; font-size: 6.5pt; color: #94A3B8;
            border-top: 1px solid #E2E8F0; padding-top: 4px;
        }
        .footer .page-number:after { content: "Halaman " counter(page); }
    </style>
</head>
<body>
    <div class="company-header">
        <div class="company-logo">PF</div>
        <div class="company-info">
            <h1>Program Formula</h1>
        </div>
    </div>

    <div class="report-title">
        <h2>{{ $title }}</h2>
        <div class="meta">Dicetak: {{ now()->format('d-m-Y H:i') }} &middot; {{ Auth::user()->name }}</div>
    </div>

    @php
        $totalKonsep = $productions->pluck('concept_id')->unique()->count();
        $totalProductions = $productions->count();
    @endphp
    <div class="summary-grid">
        <div class="summary-item">
            <div class="label">Total Produksi</div>
            <div class="value">{{ $totalProductions }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Konsep</div>
            <div class="value">{{ $totalKonsep }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">DATA PRODUKSI</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 24px;">No</th>
                    <th>Tanggal Mulai</th>
                    <th>Nama Produksi</th>
                    <th>Konsep</th>
                    <th>Jenis</th>
                    <th>Kapasitas</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productions as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->start_date?->format('d-m-Y') ?? '-' }}</td>
                        <td><strong>{{ $p->name }}</strong></td>
                        <td>{{ $p->concept?->name }}</td>
                        <td>{{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</td>
                        <td>{{ formatWeight($p->target_weight_kg) }} kg</td>
                        <td>{{ $p->location ?? '-' }}</td>
                        <td>{{ $p->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                    </tr>
                @endforeach
                @if ($productions->isEmpty())
                    <tr><td colspan="8" style="text-align:center;padding:16px;color:#94A3B8;">Tidak ada data.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        <span class="page-number"></span>
        &middot; Dicetak oleh: {{ Auth::user()->name }} &middot; {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
