<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Produksi #{{ $production->id }} — Program Formula</title>
    <style>
        @page { margin: 8mm 6mm 8mm 6mm; size: A4 portrait; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7pt;
            color: #1E293B;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }

        .card-grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .card-grid td { width: 33.33%; vertical-align: top; padding: 4px; }

        .card {
            border: 0.5px solid #CBD5E1;
            border-radius: 4px;
            padding: 5px 6px;
            background: #fff;
            page-break-inside: avoid;
        }
        .card-header {
            font-weight: 700;
            font-size: 7.5pt;
            color: #fff;
            background: #2563EB;
            padding: 2px 6px;
            border-radius: 3px 3px 0 0;
            margin: -5px -6px 4px -6px;
        }
        .card-header .meta {
            font-size: 5.5pt;
            font-weight: 400;
            color: rgba(255,255,255,0.85);
        }

        .info-bar {
            font-size: 5.5pt;
            color: #475569;
            margin-bottom: 3px;
            padding: 2px 0;
            border-bottom: 0.5px solid #E2E8F0;
        }
        .info-bar table { width: 100%; border-collapse: collapse; }
        .info-bar td { padding: 0; font-size: 5.5pt; border: none; width: 50%; }
        .info-bar .lbl { color: #94A3B8; }
        .info-bar .val { color: #334155; font-weight: 600; }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6pt;
        }
        .item-table thead th {
            background: #F1F5F9;
            color: #64748B;
            font-weight: 600;
            font-size: 5pt;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            padding: 2px 4px;
            text-align: left;
            border-bottom: 0.5px solid #CBD5E1;
        }
        .item-table tbody td {
            padding: 1.5px 4px;
            border-bottom: 0.3px solid #F1F5F9;
            font-size: 5.5pt;
        }
        .item-table tbody tr:nth-child(even) { background: #FAFAFA; }

        .sub-label {
            font-size: 5pt;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            padding: 2px 0 1px;
            margin-top: 2px;
            border-top: 0.3px solid #E2E8F0;
        }
        .sub-item {
            font-size: 5.5pt;
            color: #475569;
            padding: 0 0 0 2px;
        }
        .dosis-badge {
            display: inline-block;
            background: #FEF3C7;
            color: #B45309;
            font-size: 4.5pt;
            font-weight: 600;
            padding: 0 3px;
            border-radius: 2px;
        }
        .cek-box {
            font-size: 8pt;
            color: #94A3B8;
        }
    </style>
</head>
<body>
    @php
        $p = $production;
        $gridItems = [];
        $gridItems[] = ['lbl' => 'Tgl Campur', 'val' => $p->mix_date?->format('d-m-Y') ?? '-'];
        $gridItems[] = ['lbl' => 'Mulai Konsep', 'val' => $p->start_date?->format('d-m-Y') ?? '-'];
        $gridItems[] = ['lbl' => 'Durasi', 'val' => $p->is_forever ? 'Slm' : $p->duration_days.' hr'];
        $gridItems[] = ['lbl' => 'Lokasi', 'val' => $p->location ?? '-'];
        $gridItems[] = ['lbl' => 'Kandang', 'val' => $p->cage ?? '-'];
        $gridItems[] = ['lbl' => 'Kapasitas', 'val' => formatWeight($p->target_weight_kg).' kg'];
        $gridItems[] = ['lbl' => 'Konsep', 'val' => $p->concept?->name ?? '-'];
        $gridItems[] = ['lbl' => 'Status', 'val' => $p->is_active ? 'Aktif' : 'Nonaktif'];

        $hasItems = $p->items->isNotEmpty();
        $hasGroups = $p->groups->isNotEmpty();
        $hasTabs = $p->tabs->isNotEmpty();
        $hasNotes = $p->notes ? true : false;
    @endphp

    @php $totalCards = 18; $lastIndex = $totalCards - 1; @endphp
    <table class="card-grid">
        @for ($repeat = 0; $repeat < $totalCards; $repeat++)
            @if ($repeat % 3 === 0)
                <tr>
            @endif
            <td>
                <div class="card">
                    {{-- Header --}}
                    <div class="card-header">
                        {{ $p->name }}
                        <span class="meta">#{{ $p->id }} &middot; {{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</span>
                    </div>

                    {{-- Info Bar --}}
                    <div class="info-bar">
                        <table>
                            @foreach (array_chunk($gridItems, 2) as $rowPair)
                                <tr>
                                    @foreach ($rowPair as $item)
                                        <td><span class="lbl">{{ $item['lbl'] }}</span> <span class="val">{{ $item['val'] }}</span></td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    {{-- Items --}}
                    @if ($hasItems)
                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th style="width:14px;">#</th>
                                    <th>Item</th>
                                    <th style="width:38px;">Berat</th>
                                    <th style="width:38px;">Sumber</th>
                                    <th style="width:16px;">Cek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($p->items as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $row->item?->name }}</td>
                                        <td>{{ formatWeight($row->weight_kg) }}</td>
                                        <td>{{ $row->source }}</td>
                                        <td class="cek-box">&#9744;</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    {{-- Groups --}}
                    @if ($hasGroups)
                        <div class="sub-label">Golongan</div>
                        @foreach ($p->groups as $group)
                            <div class="sub-item">
                                <strong>{{ $group->name }}</strong>
                                @foreach ($group->items as $gi)
                                    @php
                                        $dw = $gi->weight_input_value && $gi->inputUnit
                                            ? formatWeight($gi->weight_input_value).' '.$gi->inputUnit->name
                                            : formatWeight($gi->weight_kg).' kg';
                                    @endphp
                                    <br>&#9744; {{ $gi->item?->name }}: {{ $dw }}@if($gi->is_dosis) <span class="dosis-badge">Dosis</span>@endif
                                @endforeach
                            </div>
                        @endforeach
                    @endif

                    {{-- Tabs --}}
                    @if ($hasTabs)
                        <div class="sub-label">Tab</div>
                        @foreach ($p->tabs as $tab)
                            <div class="sub-item">
                                <strong>{{ $tab->name }}</strong>
                                ({{ formatWeight($tab->input_weight_kg) }} kg)
                                @foreach ($tab->items as $ti)
                                    @php
                                        $dw = $ti->weight_input_value && $ti->inputUnit
                                            ? formatWeight($ti->weight_input_value).' '.$ti->inputUnit->name
                                            : formatWeight($ti->weight_kg).' kg';
                                    @endphp
                                    <br>&#9744; {{ $ti->item?->name }}: {{ $dw }}@if($ti->is_dosis) <span class="dosis-badge">Dosis</span>@endif
                                @endforeach
                            </div>
                        @endforeach
                    @endif

                    {{-- Notes --}}
                    @if ($hasNotes)
                        <div class="sub-label">Catatan</div>
                        <div class="sub-item" style="font-style:italic;">{{ $p->notes }}</div>
                    @endif
                </div>
            </td>
            @if ($repeat % 3 === 2 || $repeat === $lastIndex)
                @for ($i = ($repeat % 3) + 1; $i < 3; $i++)
                    <td></td>
                @endfor
                </tr>
            @endif
        @endfor
    </table>
</body>
</html>
