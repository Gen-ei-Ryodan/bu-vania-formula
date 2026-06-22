<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>{{ $title }}</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
            h1 { font-size: 16px; margin: 0 0 6px; }
            table { width: 100%; border-collapse: collapse; margin-top: 6px; }
            th, td { border: 1px solid #ddd; padding: 4px 6px; vertical-align: top; }
            th { background: #f5f5f5; text-align: left; font-size: 9px; }
        </style>
    </head>
    <body>
        <h1>{{ $title }}</h1>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Mulai</th>
                    <th>Nama Produksi</th>
                    <th>Konsep</th>
                    <th>Jenis</th>
                    <th>Kapasitas (kg)</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productions as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->start_date?->format('d-m-Y') ?? '-' }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->concept?->name }}</td>
                        <td>{{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</td>
                        <td>{{ formatWeight($p->target_weight_kg) }} kg</td>
                        <td>{{ $p->location ?? '-' }}</td>
                        <td>{{ $p->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                    </tr>
                @endforeach
                @if ($productions->isEmpty())
                    <tr><td colspan="8">Tidak ada data.</td></tr>
                @endif
            </tbody>
        </table>
    </body>
</html>
