<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Produksi #{{ $production->id }} — Program Formula</title>
    <style>
        @page { margin: 3mm; size: A4 landscape; }
        html, body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7pt;
            color: #1E293B;
            line-height: 1.25;
            margin: 0;
            padding: 0;
        }

        .card {
            position: absolute;
            border: 0.4px solid #334155;
            border-radius: 2px;
            padding: 3px 4px;
            background: #fff;
            overflow: hidden;
        }

        .card-header {
            font-weight: 700; font-size: 7.5pt; color: #fff;
            background: #1E3A5F; padding: 1.5px 4px;
            border-radius: 2px 2px 0 0;
            margin: -3px -4px 3px -4px;
            text-align: center; letter-spacing: 0.3px;
        }

        .info-grid {
            width: 100%; border-collapse: collapse;
            margin-bottom: 2px; border: 0.3px solid #CBD5E1;
        }
        .info-grid td { padding: 1px 2px; font-size: 6pt; border: 0.3px solid #CBD5E1; }
        .info-grid .lbl { font-weight: 600; color: #475569; background: #F8FAFC; width: 35%; }
        .info-grid .val { color: #1E293B; }

        .item-table {
            width: 100%; border-collapse: collapse;
            font-size: 6pt; margin-bottom: 2px;
        }
        .item-table thead th {
            background: #1E3A5F; color: #fff; font-weight: 600;
            font-size: 5.5pt; text-transform: uppercase;
            padding: 1.5px 2px; text-align: center;
            border: 0.3px solid #1E3A5F; line-height: 1.2;
        }
        .item-table tbody td {
            padding: 1px 2px; border: 0.3px solid #E2E8F0;
            font-size: 6pt; vertical-align: middle;
        }
        .item-table tbody tr:nth-child(even) { background: #FAFAFA; }
        .item-table .c-no { width: 12px; text-align: center; }
        .item-table .c-name { text-align: left; }
        .item-table .c-w { width: 28px; text-align: right; }
        .item-table .c-ttd { width: 32px; text-align: center; }

        .extra-items {
            font-size: 5.5pt; color: #475569; padding: 1px 2px;
            border-bottom: 0.3px solid #E2E8F0; margin-bottom: 1px;
        }
        .extra-items .label { font-weight: 700; color: #1E3A5F; }
        .extra-items .sub { padding-left: 8px; color: #64748B; }
        .dosis-tag { font-size: 5pt; color: #B45309; }

        .notes-line { font-size: 5.5pt; color: #64748B; font-style: italic; padding: 1px 2px; min-height: 8px; }
    </style>
</head>
<body>
    @php
        $p = $production;
        $totalCards = $totalCards ?? 9;
        $cols = $totalCards <= 4 ? 2 : 3;
        $rows = (int) ceil($totalCards / $cols);
        $colW = $cols === 2 ? 49.8 : 33.1;
        $rowH = $rows === 1 ? 99.6 : ($rows === 2 ? 49.6 : 33.0);
        $gap = 0.4;
    @endphp

    @for ($r = 0; $r < $rows; $r++)
        @php $leftInRow = $totalCards - ($r * $cols); $thisRow = min($cols, $leftInRow); @endphp
        @for ($c = 0; $c < $thisRow; $c++)
            @php
                $top = $r * ($rowH + $gap);
                $left = $c * ($colW + $gap);
            @endphp
            <div class="card" style="top: {{ $top }}%; left: {{ $left }}%; width: {{ $colW }}%; height: {{ $rowH }}%;">
                <div class="card-header">FORMULIR PRODUKSI &mdash; {{ $p->name }}</div>

                <table class="info-grid">
                    <tr>
                        <td class="lbl">Tgl Produksi</td>
                        <td class="val">{{ $p->mix_date?->format('d-m-Y') ?? ($p->start_date?->format('d-m-Y') ?? '-') }}</td>
                        <td class="lbl">Kapasitas</td>
                        <td class="val">{{ formatWeight($p->target_weight_kg) }} kg</td>
                    </tr>
                    <tr>
                        <td class="lbl">Kode Formula</td>
                        <td class="val">{{ $p->concept?->name ?? '-' }}</td>
                        <td class="lbl">Batch</td>
                        <td class="val">-</td>
                    </tr>
                    <tr>
                        <td class="lbl">Nama Formula</td>
                        <td class="val" colspan="3">{{ $p->concept?->name ?? '-' }}</td>
                    </tr>
                </table>

                <table class="item-table">
                    <thead>
                        <tr>
                            <th class="c-no">No</th>
                            <th class="c-name">Nama Bahan</th>
                            <th class="c-w">Kg</th>
                            <th class="c-ttd">Cek Penimbangan OBAT (TTD)</th>
                            <th class="c-ttd">Cek Pencampuran MIXER KECIL (TTD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($p->items as $i => $row)
                            <tr>
                                <td class="c-no">{{ $i + 1 }}</td>
                                <td class="c-name">{{ $row->item?->name }}</td>
                                <td class="c-w">{{ formatWeight($row->weight_kg) }}</td>
                                <td class="c-ttd"></td>
                                <td class="c-ttd"></td>
                            </tr>
                        @empty
                            <tr><td class="c-no" colspan="5" style="text-align:center;color:#CBD5E1;">-</td></tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($p->groups->isNotEmpty())
                    @foreach ($p->groups as $group)
                        <div class="extra-items">
                            <span class="label">{{ $group->name }}</span>
                            @foreach ($group->items as $gi)
                                @php
                                    $dw = $gi->weight_input_value && $gi->inputUnit
                                        ? round($gi->weight_input_value, 4) . ' ' . $gi->inputUnit->name
                                        : formatWeight($gi->weight_kg) . ' kg';
                                @endphp
                                <br><span class="sub">&mdash; {{ $gi->item?->name }}: {{ $dw }}@if($gi->is_dosis) <span class="dosis-tag">[Dosis]</span>@endif</span>
                            @endforeach
                        </div>
                    @endforeach
                @endif

                @if ($p->tabs->isNotEmpty())
                    @foreach ($p->tabs as $tab)
                        <div class="extra-items">
                            <span class="label">TAB: {{ $tab->name }} ({{ formatWeight($tab->input_weight_kg) }} kg)</span>
                            @foreach ($tab->items as $ti)
                                @php
                                    $dw = $ti->weight_input_value && $ti->inputUnit
                                        ? round($ti->weight_input_value, 4) . ' ' . $ti->inputUnit->name
                                        : formatWeight($ti->weight_kg) . ' kg';
                                @endphp
                                <br><span class="sub">&mdash; {{ $ti->item?->name }}: {{ $dw }}@if($ti->is_dosis) <span class="dosis-tag">[Dosis]</span>@endif</span>
                            @endforeach
                        </div>
                    @endforeach
                @endif

                <div class="notes-line">
                    @if ($p->notes)
                        {{ $p->notes }}
                    @else
                        &nbsp;
                    @endif
                </div>
            </div>
        @endfor
    @endfor
</body>
</html>
