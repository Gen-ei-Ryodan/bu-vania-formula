<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Laporan</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
            h1 { font-size: 16px; margin: 0 0 6px; }
            h2 { font-size: 12px; margin: 12px 0 6px; }
            table { width: 100%; border-collapse: collapse; margin-top: 6px; }
            th, td { border: 1px solid #ddd; padding: 4px 6px; vertical-align: top; }
            th { background: #f5f5f5; text-align: left; font-size: 9px; }
            .info { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 10px; }
            .info > div { font-size: 11px; }
            .info strong { display: block; font-size: 13px; }
            .chip { display: inline-block; background: #e8f4fd; padding: 2px 6px; border-radius: 4px; font-size: 9px; }
        </style>
    </head>
    <body>
        <h1>Laporan Produksi</h1>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Tgl Campur</th>
                    <th>Tgl Mulai Pakai Konsep</th>
                    <th>Durasi</th>
                    <th>Lokasi</th>
                    <th>Kandang</th>
                    <th>Kapasitas</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productions as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</td>
                        <td>{{ $p->mix_date?->format('d-m-Y') ?? '-' }}</td>
                        <td>{{ $p->start_date?->format('d-m-Y') ?? '-' }}</td>
                        <td>{{ $p->is_forever ? 'Selamanya' : $p->duration_days.' hari' }}</td>
                        <td>{{ $p->location ?? '-' }}</td>
                        <td>{{ $p->cage ?? '-' }}</td>
                        <td>{{ formatWeight($p->target_weight_kg) }} kg</td>
                        <td>{{ $p->notes ?? '-' }}</td>
                    </tr>
                @endforeach
                @if ($productions->isEmpty())
                    <tr><td colspan="10">Tidak ada data.</td></tr>
                @endif
            </tbody>
        </table>

        @foreach ($productions as $production)
            <div style="page-break-before: always;">
                <h2>{{ $production->name }} ({{ $production->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }})</h2>
                <div class="info">
                    <div><span>Tanggal Campur</span><strong>{{ $production->mix_date?->format('d-m-Y') ?? '-' }}</strong></div>
                    <div><span>Tanggal Mulai Pakai Konsep</span><strong>{{ $production->start_date?->format('d-m-Y') ?? '-' }}</strong></div>
                    <div><span>Durasi</span><strong>{{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</strong></div>
                    <div><span>Lokasi</span><strong>{{ $production->location ?? '-' }}</strong></div>
                    <div><span>Kandang</span><strong>{{ $production->cage ?? '-' }}</strong></div>
                    <div><span>Kapasitas</span><strong>{{ formatWeight($production->target_weight_kg) }} kg</strong></div>
                    @if ($production->notes)
                        <div><span>Catatan</span><strong>{{ $production->notes }}</strong></div>
                    @endif
                </div>

                <h3>Snapshot Item</h3>
                <table>
                    <thead>
                        <tr><th>Item</th><th style="width: 100px;">Berat (kg)</th><th style="width: 80px;">Sumber</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($production->items as $row)
                            <tr>
                                <td>{{ $row->item?->name }}</td>
                                <td>{{ formatWeight($row->weight_kg) }}</td>
                                <td>{{ $row->source }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($production->groups->isNotEmpty())
                    <h3>Golongan</h3>
                    @foreach ($production->groups as $group)
                        <div><strong>{{ $group->name }}</strong></div>
                        <table>
                            <thead>
                                <tr><th>Item</th><th style="width: 100px;">Berat</th><th style="width: 60px;">Dosis</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($group->items as $gi)
                                    @php
                                        $displayW = $gi->weight_input_value && $gi->inputUnit
                                            ? formatWeight($gi->weight_input_value).' '.$gi->inputUnit->name
                                            : formatWeight($gi->weight_kg).' kg';
                                    @endphp
                                    <tr>
                                        <td>{{ $gi->item?->name }}</td>
                                        <td>{{ $displayW }}</td>
                                        <td>{{ $gi->is_dosis ? 'Dosis' : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                @endif

                @if ($production->tabs->isNotEmpty())
                    <h3>TAB</h3>
                    @foreach ($production->tabs as $tab)
                        <div>
                            <strong>{{ $tab->name }}</strong>
                            (Ambil: {{ formatWeight($tab->input_weight_kg) }} kg, Sisa: {{ formatWeight($tab->remaining_weight_kg) }} kg)
                        </div>
                        <table>
                            <thead>
                                <tr><th>Item</th><th style="width: 100px;">Berat</th><th style="width: 60px;">Dosis</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($tab->items as $ti)
                                    @php
                                        $displayW = $ti->weight_input_value && $ti->inputUnit
                                            ? formatWeight($ti->weight_input_value).' '.$ti->inputUnit->name
                                            : formatWeight($ti->weight_kg).' kg';
                                    @endphp
                                    <tr>
                                        <td>{{ $ti->item?->name }}</td>
                                        <td>{{ $displayW }}</td>
                                        <td>{{ $ti->is_dosis ? 'Dosis' : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                @endif
            </div>
        @endforeach
    </body>
</html>
