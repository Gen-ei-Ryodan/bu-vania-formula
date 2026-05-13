<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Production {{ $production->id }}</title>
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
        <h1>Production #{{ $production->id }} - {{ $production->name }}</h1>

        <table class="meta">
            <tr>
                <td><strong>Nama Bibit:</strong> {{ $production->seed_name }}</td>
            </tr>
            <tr>
                <td><strong>Concept:</strong> {{ $production->concept?->name }}</td>
            </tr>
            <tr>
                <td><strong>Target (kg):</strong> {{ number_format($production->target_weight_kg, 2) }}</td>
            </tr>
            @if ($production->start_date || $production->end_date)
                <tr>
                    <td><strong>Periode:</strong> {{ $production->start_date?->format('d-m-Y') }} s/d {{ $production->end_date?->format('d-m-Y') }}</td>
                </tr>
            @endif
            @if ($production->notes)
                <tr>
                    <td><strong>Notes:</strong> {{ $production->notes }}</td>
                </tr>
            @endif
        </table>

        <h2>Snapshot Production Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="width: 140px;">Weight (kg)</th>
                    <th style="width: 120px;">Source</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($production->items as $row)
                    <tr>
                        <td>{{ $row->item?->name }}</td>
                        <td>{{ number_format($row->weight_kg, 2) }}</td>
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

        <h2>Golongan (Global Add-on)</h2>
        @foreach ($production->groups as $group)
            <div><strong>{{ $group->name }}</strong></div>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="width: 140px;">Weight (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($group->items as $gi)
                        <tr>
                            <td>{{ $gi->item?->name }}</td>
                            <td>{{ number_format($gi->weight_kg, 2) }}</td>
                        </tr>
                    @endforeach
                    @if ($group->items->isEmpty())
                        <tr>
                            <td colspan="2">Belum ada item.</td>
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
                (Ambil: {{ number_format($tab->input_weight_kg, 2) }} kg, Sisa: {{ number_format($tab->remaining_weight_kg, 2) }} kg)
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="width: 140px;">Weight (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tab->items as $ti)
                        <tr>
                            <td>{{ $ti->item?->name }}</td>
                            <td>{{ number_format($ti->weight_kg, 2) }}</td>
                        </tr>
                    @endforeach
                    @if ($tab->items->isEmpty())
                        <tr>
                            <td colspan="2">Belum ada item.</td>
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

