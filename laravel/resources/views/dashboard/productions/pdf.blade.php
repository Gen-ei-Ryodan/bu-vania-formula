<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Produksi {{ $production->id }}</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
            h1 { font-size: 18px; margin: 0 0 10px; }
            h2 { font-size: 14px; margin: 18px 0 8px; }
            table { width: 100%; border-collapse: collapse; margin-top: 6px; }
            th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; }
            th { background: #f5f5f5; text-align: left; }
            .meta { width: 100%; }
            .meta td { border: 0; padding: 2px 0; }
        </style>
    </head>
    <body>
        <h1>Produksi #{{ $production->id }} - {{ $production->name }}</h1>

        <table class="meta">
            <tr>
                <td><strong>Tgl Mulai:</strong> {{ $production->start_date?->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td><strong>Konsep:</strong> {{ $production->concept?->name }}</td>
            </tr>
            <tr>
                <td><strong>Target (kg):</strong> {{ formatWeight($production->target_weight_kg) }}</td>
            </tr>
            @if ($production->start_date)
                <tr>
                    <td><strong>Tgl Mulai:</strong> {{ $production->start_date?->format('d-m-Y') }}</td>
                </tr>
            @endif
            @if ($production->mix_date)
                <tr>
                    <td><strong>Tanggal Campur:</strong> {{ $production->mix_date?->format('d-m-Y') }}</td>
                </tr>
            @endif
            <tr>
                <td><strong>Durasi:</strong> {{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</td>
            </tr>
            @if ($production->notes)
                <tr>
                    <td><strong>Catatan:</strong> {{ $production->notes }}</td>
                </tr>
            @endif
        </table>

        <h2>Snapshot Item Produksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="width: 140px;">Berat (kg)</th>
                    <th style="width: 120px;">Sumber</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($production->items as $row)
                    <tr>
                        <td>{{ $row->item?->name }}</td>
                        <td>{{ formatWeight($row->weight_kg) }}</td>
                        <td>{{ $row->source }}</td>
                    </tr>
                @endforeach
                @if ($production->items->isEmpty())
                    <tr>
                        <td colspan="3">Belum ada snapshot.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <h2>Golongan (Add-on Global)</h2>
        @foreach ($production->groups as $group)
            <div><strong>{{ $group->name }}</strong></div>
            <table>
                <thead>
                    <tr>
                        <th>Tambah Item</th>
                        <th style="width: 120px;">Berat (kg)</th>
                        <th style="width: 120px;">Dibuat</th>
                    </tr>
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
                            <td>{{ $gi->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                    @endforeach
                    @if ($group->items->isEmpty())
                        <tr>
                            <td colspan="3">Belum ada item.</td>
                        </tr>
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
                        <th>Tambah Item</th>
                        <th style="width: 120px;">Berat (kg)</th>
                        <th style="width: 120px;">Dibuat</th>
                    </tr>
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
                            <td>{{ $ti->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                    @endforeach
                    @if ($tab->items->isEmpty())
                        <tr>
                            <td colspan="3">Belum ada item.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
        @if ($production->tabs->isEmpty())
            <div>Belum ada TAB.</div>
        @endif
    </body>
</html>

