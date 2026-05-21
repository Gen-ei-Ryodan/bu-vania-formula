<x-layouts.dashboard title="Produksi Pengobatan" heading="Produksi Pengobatan">
    <div class="panel">
        <div class="panel-header">
            <h2>Pengobatan</h2>
            <a class="btn btn-primary" href="{{ route('treatments.create') }}">Buat</a>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Hari Ke</th>
                        <th>Waktu</th>
                        <th>Tgl Mulai</th>
                        <th>Konsep</th>
                        <th>Target (kg)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productions as $production)
                        <tr>
                            <td>{{ $production->id }}</td>
                            <td>{{ $production->name }}</td>
                            <td>{{ $production->treatment_day ?? '-' }}</td>
                            <td><span class="chip">{{ $production->treatment_time ?? '-' }}</span></td>
                            <td>{{ $production->start_date?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ $production->concept?->name }}</td>
                            <td>{{ formatWeight($production->target_weight_kg) }}</td>
                            <td>
                                <a class="btn" href="{{ route('treatments.show', $production) }}">Detail</a>
                                <a class="btn" href="{{ route('treatments.edit', $production) }}">Edit</a>
                                <a class="btn" href="{{ route('treatments.pdf', $production) }}">PDF</a>
                                <form method="POST" action="{{ route('treatments.destroy', $production) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($productions->isEmpty())
                        <tr>
                            <td colspan="8" class="muted">Belum ada data.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
