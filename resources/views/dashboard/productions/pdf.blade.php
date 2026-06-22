<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Produksi #{{ $production->id }} — Program Formula</title>
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

        .report-title { margin-bottom: 14px; }
        .report-title h2 { margin: 0; font-size: 12pt; font-weight: 700; color: #0F172A; }
        .report-title .meta { font-size: 7pt; color: #94A3B8; margin-top: 2px; }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            overflow: hidden;
        }
        .info-table td {
            border: 1px solid #E2E8F0;
            padding: 6px 8px;
            background: #ffffff;
            vertical-align: top;
            width: 25%;
        }
        .info-table td .lbl { font-size: 6pt; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 1px; }
        .info-table td .val { font-size: 7.5pt; font-weight: 600; color: #0F172A; }

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

        .sub-section { margin-top: 8px; }
        .sub-header { font-weight: 700; font-size: 7.5pt; padding: 4px 0; color: #334155; }
        .dosis-badge {
            display: inline-block; background: #FEF3C7; color: #B45309;
            font-size: 6.5pt; font-weight: 600; padding: 1px 6px; border-radius: 4px;
        }

        .ttd-box {
            border: 1px solid #CBD5E1; padding: 4px;
            display: inline-block; min-width: 140px;
            border-radius: 4px;
        }
        .ttd-box div { font-size: 7pt; color: #64748B; text-align: center; }
        .ttd-space { height: 28px; }

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
        <h2>LAPORAN PRODUKSI</h2>
        <div class="meta">#{{ $production->id }} &middot; {{ $production->name }} &middot; Dicetak: {{ now()->format('d-m-Y H:i') }}</div>
    </div>

    @php
        $gridItems = [];
        $gridItems[] = ['lbl' => 'Tanggal Campur', 'val' => $production->mix_date?->format('d-m-Y') ?? '-'];
        $gridItems[] = ['lbl' => 'Mulai Pakai Konsep', 'val' => $production->start_date?->format('d-m-Y') ?? '-'];
        $gridItems[] = ['lbl' => 'Durasi', 'val' => $production->is_forever ? 'Selamanya' : $production->duration_days.' hari'];
        $gridItems[] = ['lbl' => 'Lokasi', 'val' => $production->location ?? '-'];
        $gridItems[] = ['lbl' => 'Kandang', 'val' => $production->cage ?? '-'];
        $gridItems[] = ['lbl' => 'Kapasitas', 'val' => formatWeight($production->target_weight_kg).' kg'];
        $gridItems[] = ['lbl' => 'Konsep', 'val' => $production->concept?->name ?? '-'];
        $gridItems[] = ['lbl' => 'Status', 'val' => $production->is_active ? 'Aktif' : 'Tidak Aktif'];
        $hasNotes = $production->notes ? true : false;
    @endphp
    <table class="info-table">
        @foreach (array_chunk($gridItems, 4) as $rowItems)
            <tr>
                @for ($c = 0; $c < 4; $c++)
                    @if (isset($rowItems[$c]))
                        <td>
                            <div class="lbl">{{ $rowItems[$c]['lbl'] }}</div>
                            <div class="val">{{ $rowItems[$c]['val'] }}</div>
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
            </tr>
        @endforeach
        @if ($hasNotes)
            <tr>
                <td colspan="4">
                    <div class="lbl">Catatan</div>
                    <div class="val">{{ $production->notes }}</div>
                </td>
            </tr>
        @endif
    </table>

    <div class="section">
        <div class="section-header">SNAPSHOT ITEM PRODUKSI</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 24px;">No</th>
                    <th>Item</th>
                    <th style="width: 60px;">Berat (kg)</th>
                    <th style="width: 70px;">Sumber</th>
                    <th style="width: 28px;">Cek</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($production->items as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->item?->name }}</td>
                        <td>{{ formatWeight($row->weight_kg) }}</td>
                        <td>{{ $row->source }}</td>
                        <td style="text-align:center;">&#9744;</td>
                    </tr>
                @endforeach
                @if ($production->items->isEmpty())
                    <tr><td colspan="5" style="color:#94A3B8;padding:8px;text-align:center;">Belum ada snapshot.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($production->groups->isNotEmpty())
        <div class="section">
            <div class="section-header">GOLONGAN (ADD-ON GLOBAL)</div>
            @foreach ($production->groups as $group)
                <div style="margin-bottom:8px;page-break-inside:avoid;">
                    <div class="sub-header">{{ $group->name }}</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width:24px;">No</th>
                                <th>Item</th>
                                <th style="width:60px;">Berat</th>
                                <th style="width:50px;">Dosis</th>
                                <th style="width:28px;">Cek</th>
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
                                    <td style="text-align:center;">&#9744;</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

    @if ($production->tabs->isNotEmpty())
        <div class="section">
            <div class="section-header">TAB (SPLIT BATCH)</div>
            @foreach ($production->tabs as $tab)
                <div style="margin-bottom:8px;page-break-inside:avoid;">
                    <div class="sub-header">
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
                                <th style="width:28px;">Cek</th>
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
                                    <td style="text-align:center;">&#9744;</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

    <br>
    <table style="width:100%;border:0;">
        <tr>
            <td style="width:50%;border:0;text-align:center;padding-top:16px;">
                <div class="ttd-box">
                    <div>Penimbang</div>
                    <div class="ttd-space"></div>
                    <div>( __________________ )</div>
                </div>
            </td>
            <td style="width:50%;border:0;text-align:center;padding-top:16px;">
                <div class="ttd-box">
                    <div>Mengetahui</div>
                    <div class="ttd-space"></div>
                    <div>( __________________ )</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <span class="page-number"></span>
        &middot; Dicetak oleh: {{ Auth::user()->name }} &middot; {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
