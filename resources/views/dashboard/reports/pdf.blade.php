<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi — Program Formula</title>
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

        /* ─── Company Header ─── */
        .company-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2563EB;
            margin-bottom: 14px;
        }
        .company-logo {
            width: 48px; height: 48px;
            background: #2563EB;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }
        .company-info h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: 700;
            color: #0F172A;
        }
        .company-info p {
            margin: 1px 0 0;
            font-size: 7.5pt;
            color: #64748B;
        }

        /* ─── Report Title ─── */
        .report-title {
            text-align: center;
            margin-bottom: 12px;
        }
        .report-title h2 {
            margin: 0;
            font-size: 12pt;
            font-weight: 700;
            color: #0F172A;
        }
        .report-title .meta {
            font-size: 7pt;
            color: #94A3B8;
            margin-top: 2px;
        }

        /* ─── Summary Box ─── */
        .summary-grid {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 14px;
            padding: 10px 12px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
        }
        .summary-item {
            flex: 1;
            min-width: 80px;
        }
        .summary-item .label {
            font-size: 6.5pt;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .summary-item .value {
            font-size: 10pt;
            font-weight: 700;
            color: #0F172A;
        }

        /* ─── Section ─── */
        .section {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .section-header {
            background: #2563EB;
            color: #fff;
            padding: 5px 10px;
            font-size: 8.5pt;
            font-weight: 700;
            border-radius: 4px 4px 0 0;
            page-break-after: avoid;
        }

        /* ─── Info Grid ─── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #E2E8F0;
            border-top: none;
        }
        .info-table td {
            border: 1px solid #E2E8F0;
            padding: 5px 7px;
            background: #F8FAFC;
            vertical-align: top;
            width: 25%;
        }
        .info-table td .lbl {
            font-size: 6pt;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1px;
        }
        .info-table td .val {
            font-size: 7.5pt;
            font-weight: 600;
            color: #0F172A;
        }

        /* ─── Table ─── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-top: 0;
        }
        thead th {
            background: #F1F5F9;
            color: #475569;
            font-weight: 600;
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 5px 8px;
            text-align: left;
            border-bottom: 1.5px solid #CBD5E1;
        }
        tbody td {
            padding: 4px 8px;
            border-bottom: 0.5px solid #E2E8F0;
        }
        tbody tr:nth-child(even) {
            background: #F8FAFC;
        }
        tbody tr:last-child td {
            border-bottom: 1px solid #CBD5E1;
        }

        /* ─── Sub section inside ─── */
        .sub-section {
            margin-top: 8px;
        }
        .sub-header {
            font-weight: 700;
            font-size: 7.5pt;
            padding: 4px 0;
            color: #334155;
        }

        /* ─── Dosis badge ─── */
        .dosis-badge {
            display: inline-block;
            background: #FEF3C7;
            color: #B45309;
            font-size: 6.5pt;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 4px;
        }

        /* ─── Footer ─── */
        .footer {
            position: fixed;
            bottom: -16mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 6.5pt;
            color: #94A3B8;
            border-top: 1px solid #E2E8F0;
            padding-top: 4px;
        }
        .footer .page-number:after {
            content: "Halaman " counter(page);
        }

        /* ─── Misc ─── */
        .empty-state {
            padding: 12px;
            text-align: center;
            font-size: 7pt;
            color: #94A3B8;
            border: 1px solid #E2E8F0;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <!-- Company Header -->
    <div class="company-header">
        <div class="company-logo">PF</div>
        <div class="company-info">
            <h1>Program Formula</h1>
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">
        <h2>LAPORAN PRODUKSI</h2>
        <div class="meta">Dicetak: {{ now()->format('d-m-Y H:i') }} &middot; {{ Auth::user()->name }}</div>
    </div>

    <!-- Summary -->
    @php
        $totalBiasa = $productions->filter(fn($p) => $p->production_type === 'biasa')->count();
        $totalTreatment = $productions->filter(fn($p) => $p->production_type === 'treatment')->count();
        $locations = $productions->pluck('location')->unique()->filter()->values();
        $cages = $productions->pluck('cage')->unique()->filter()->values();
    @endphp
    <div class="summary-grid">
        <div class="summary-item">
            <div class="label">Total Produksi</div>
            <div class="value">{{ $productions->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Biasa</div>
            <div class="value">{{ $totalBiasa }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Pengobatan</div>
            <div class="value">{{ $totalTreatment }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Lokasi</div>
            <div class="value">{{ $locations->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Kandang</div>
            <div class="value">{{ $cages->count() }}</div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="section">
        <div class="section-header">RINGKASAN PRODUKSI</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 24px;">#</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Mulai</th>
                    <th>Durasi</th>
                    <th>Lokasi</th>
                    <th>Kandang</th>
                    <th style="width: 50px;">Kapasitas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productions as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $p->name }}</strong></td>
                        <td>{{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</td>
                        <td>{{ $p->start_date?->format('d-m-Y') ?? '-' }}</td>
                        <td>{{ $p->is_forever ? 'Selamanya' : $p->duration_days.' hr' }}</td>
                        <td>{{ $p->location ?? '-' }}</td>
                        <td>{{ $p->cage ?? '-' }}</td>
                        <td>{{ formatWeight($p->target_weight_kg) }}</td>
                        <td>{{ $p->is_active ? 'Aktif' : '-' }}</td>
                    </tr>
                @endforeach
                @if ($productions->isEmpty())
                    <tr><td colspan="9" style="text-align:center;padding:16px;color:#94A3B8;">Tidak ada data.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Detail per Production -->
    @foreach ($productions as $production)
        <div class="section">
            <div class="section-header">
                {{ $production->name }}
                @if ($production->production_type === 'treatment')
                    &mdash; Pengobatan (Hari ke-{{ $production->treatment_day ?? '-' }})
                @endif
            </div>

            @php
                $rItems = [];
                $rItems[] = ['lbl' => 'Mulai Pakai Konsep', 'val' => $production->start_date?->format('d-m-Y') ?? '-'];
                $rItems[] = ['lbl' => 'Durasi', 'val' => $production->is_forever ? 'Selamanya' : $production->duration_days.' hari'];
                $rItems[] = ['lbl' => 'Lokasi', 'val' => $production->location ?? '-'];
                $rItems[] = ['lbl' => 'Kandang', 'val' => $production->cage ?? '-'];
                $rItems[] = ['lbl' => 'Kapasitas', 'val' => formatWeight($production->target_weight_kg).' kg'];
            @endphp
            <table class="info-table">
                @foreach (array_chunk($rItems, 4) as $rRow)
                    <tr>
                        @for ($c = 0; $c < 4; $c++)
                            @if (isset($rRow[$c]))
                                <td>
                                    <div class="lbl">{{ $rRow[$c]['lbl'] }}</div>
                                    <div class="val">{{ $rRow[$c]['val'] }}</div>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endfor
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4">
                        <div class="lbl">Catatan</div>
                        <div class="val">{{ $production->notes ?: '-' }}</div>
                    </td>
                </tr>
            </table>

            <!-- Snapshot -->
            <table style="margin-top: 6px;">
                <thead>
                    <tr>
                        <th style="width: 24px;">No</th>
                        <th>Item</th>
                        <th style="width: 60px;" class="text-right">Berat (kg)</th>
                        <th style="width: 70px;">Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($production->items as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->item?->name }}</td>
                            <td>{{ formatWeight($row->weight_kg) }}</td>
                            <td>{{ $row->source }}</td>
                        </tr>
                    @endforeach
                    @if ($production->items->isEmpty())
                        <tr><td colspan="4" style="color:#94A3B8;padding:8px;text-align:center;">Belum ada snapshot.</td></tr>
                    @endif
                </tbody>
            </table>

            <!-- Groups -->
            @if ($production->groups->isNotEmpty())
                <div class="sub-section">
                    <div class="sub-header">Golongan</div>
                    @foreach ($production->groups as $group)
                        <div style="margin-bottom:6px;page-break-inside:avoid;">
                            <div style="font-weight:600;font-size:7pt;padding:2px 0;color:#475569;">{{ $group->name }}</div>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:24px;">No</th>
                                        <th>Item</th>
                                        <th style="width:60px;">Berat</th>
                                        <th style="width:50px;">Dosis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group->items as $gi)
                                        @php
                                            $dw = $gi->weight_input_value && $gi->inputUnit
                                                ? formatWeight($gi->weight_input_value).' '.$gi->inputUnit->name
                                                : formatWeight($gi->weight_kg).' kg';
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $gi->item?->name }}</td>
                                            <td>{{ $dw }}</td>
                                            <td>@if($gi->is_dosis)<span class="dosis-badge">Dosis</span>@else - @endif</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Tabs -->
            @if ($production->tabs->isNotEmpty())
                <div class="sub-section">
                    <div class="sub-header">Tab (Split Batch)</div>
                    @foreach ($production->tabs as $tab)
                        <div style="margin-bottom:6px;page-break-inside:avoid;">
                            <div style="font-weight:600;font-size:7pt;padding:2px 0;color:#475569;">
                                {{ $tab->name }}
                                &mdash; Ambil: {{ formatWeight($tab->input_weight_kg) }} kg, Sisa: {{ formatWeight($tab->remaining_weight_kg) }} kg
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:24px;">No</th>
                                        <th>Item</th>
                                        <th style="width:60px;">Berat</th>
                                        <th style="width:50px;">Dosis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tab->items as $ti)
                                        @php
                                            $dw = $ti->weight_input_value && $ti->inputUnit
                                                ? formatWeight($ti->weight_input_value).' '.$ti->inputUnit->name
                                                : formatWeight($ti->weight_kg).' kg';
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $ti->item?->name }}</td>
                                            <td>{{ $dw }}</td>
                                            <td>@if($ti->is_dosis)<span class="dosis-badge">Dosis</span>@else - @endif</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <span class="page-number"></span>
        &middot; Dicetak oleh: {{ Auth::user()->name }} &middot; {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
