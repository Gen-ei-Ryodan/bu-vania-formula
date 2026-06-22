<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Produksi {{ $production->id }}</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
            h1 { font-size: 16px; margin: 0 0 8px; }
            h2 { font-size: 13px; margin: 14px 0 6px; }
            table { width: 100%; border-collapse: collapse; margin-top: 6px; }
            th, td { border: 1px solid #ddd; padding: 5px; vertical-align: top; }
            th { background: #f5f5f5; text-align: left; }
            .meta { width: 100%; }
            .meta td { border: 0; padding: 2px 0; }
            .cb-col { width: 60px; text-align: center; }
            .ttd-box { border: 1px solid #ddd; padding: 6px; margin-top: 20px; display: inline-block; min-width: 200px; }
            .ttd-box div { font-size: 10px; color: #666; text-align: center; }
            .ttd-space { height: 50px; }
        </style>
    </head>
    <body>
        <h1>Produksi #{{ $production->id }} - {{ $production->name }}</h1>

        <table class="meta">
            <tr><td><strong>Tanggal Campur:</strong> {{ $production->mix_date?->format('d-m-Y') ?? '-' }}</td></tr>
            <tr><td><strong>Tanggal Mulai Pakai Konsep:</strong> {{ $production->start_date?->format('d-m-Y') ?? '-' }}</td></tr>
            <tr><td><strong>Durasi:</strong> {{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</td></tr>
            <tr><td><strong>Lokasi:</strong> {{ $production->location ?? '-' }}</td></tr>
            <tr><td><strong>Kandang:</strong> {{ $production->cage ?? '-' }}</td></tr>
            <tr><td><strong>Kapasitas:</strong> {{ formatWeight($production->target_weight_kg) }} kg</td></tr>
            <tr><td><strong>Konsep:</strong> {{ $production->concept?->name }}</td></tr>
            <tr><td><strong>Status:</strong> {{ $production->is_active ? 'Aktif' : 'Tidak Aktif' }}</td></tr>
            @if ($production->notes)
                <tr><td><strong>Catatan:</strong> {{ $production->notes }}</td></tr>
            @endif
        </table>

        <h2>Snapshot Item Produksi</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th style="width: 120px;">Berat (kg)</th>
                    <th style="width: 80px;">Sumber</th>
                    <th class="cb-col">Cek &#9744;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($production->items as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->item?->name }}</td>
                        <td>{{ formatWeight($row->weight_kg) }}</td>
                        <td>{{ $row->source }}</td>
                        <td class="cb-col"></td>
                    </tr>
                @endforeach
                @if ($production->items->isEmpty())
                    <tr><td colspan="5">Belum ada snapshot.</td></tr>
                @endif
            </tbody>
        </table>

        <h2>Golongan (Add-on Global)</h2>
        @foreach ($production->groups as $group)
            <div><strong>{{ $group->name }}</strong></div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th style="width: 120px;">Berat</th>
                        <th style="width: 80px;">Dosis</th>
                        <th class="cb-col">Cek &#9744;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($group->items as $i => $gi)
                        @php
                            $displayW = $gi->weight_input_value && $gi->inputUnit
                                ? formatWeight($gi->weight_input_value).' '.$gi->inputUnit->name
                                : formatWeight($gi->weight_kg).' kg';
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $gi->item?->name }}</td>
                            <td>{{ $displayW }}</td>
                            <td>{{ $gi->is_dosis ? 'Dosis' : '-' }}</td>
                            <td class="cb-col"></td>
                        </tr>
                    @endforeach
                    @if ($group->items->isEmpty())
                        <tr><td colspan="5">Belum ada item.</td></tr>
                    @endif
                </tbody>
            </table>
        @endforeach
        @if ($production->groups->isEmpty())
            <div>Belum ada golongan.</div>
        @endif

        <h2>TAB (Split Batch)</h2>
        @foreach ($production->tabs as $tab)
            <div>
                <strong>{{ $tab->name }}</strong>
                (Ambil: {{ formatWeight($tab->input_weight_kg) }} kg, Sisa: {{ formatWeight($tab->remaining_weight_kg) }} kg)
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th style="width: 120px;">Berat</th>
                        <th style="width: 80px;">Dosis</th>
                        <th class="cb-col">Cek &#9744;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tab->items as $i => $ti)
                        @php
                            $displayW = $ti->weight_input_value && $ti->inputUnit
                                ? formatWeight($ti->weight_input_value).' '.$ti->inputUnit->name
                                : formatWeight($ti->weight_kg).' kg';
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $ti->item?->name }}</td>
                            <td>{{ $displayW }}</td>
                            <td>{{ $ti->is_dosis ? 'Dosis' : '-' }}</td>
                            <td class="cb-col"></td>
                        </tr>
                    @endforeach
                    @if ($tab->items->isEmpty())
                        <tr><td colspan="5">Belum ada item.</td></tr>
                    @endif
                </tbody>
            </table>
        @endforeach
        @if ($production->tabs->isEmpty())
            <div>Belum ada TAB.</div>
        @endif

        <br><br>
        <table style="width: 100%; border: 0;">
            <tr>
                <td style="width: 50%; border: 0; text-align: center; padding-top: 20px;">
                    <div class="ttd-box">
                        <div>Penimbang</div>
                        <div class="ttd-space"></div>
                        <div>( __________________ )</div>
                    </div>
                </td>
                <td style="width: 50%; border: 0; text-align: center; padding-top: 20px;">
                    <div class="ttd-box">
                        <div>Mengetahui</div>
                        <div class="ttd-space"></div>
                        <div>( __________________ )</div>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>
