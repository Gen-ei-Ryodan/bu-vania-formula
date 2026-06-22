<x-layouts.dashboard title="Produksi Pengobatan" heading="Pengobatan">
    <div class="page-hero">
        <h1>Pengobatan</h1>
        <p>Daftar produksi pengobatan ternak</p>
        <div class="page-hero-actions">
            <a class="btn btn-primary" href="{{ route('treatments.create') }}">+ Buat Pengobatan</a>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h3>Pengobatan ({{ $productions->count() }})</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Kandang</th>
                                <th>Hari Ke</th>
                                <th>Waktu</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th style="width: 260px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productions as $production)
                                <tr>
                                    <td class="cell-muted">#{{ $production->id }}</td>
                                    <td><strong>{{ $production->name }}</strong></td>
                                    <td class="cell-muted">{{ $production->location ?? '-' }}</td>
                                    <td class="cell-muted">{{ $production->cage ?? '-' }}</td>
                                    <td class="cell-muted">{{ $production->treatment_day ?? '-' }}</td>
                                    <td>
                                        @if ($production->treatment_time)
                                            <span class="badge badge-muted">{{ ucfirst($production->treatment_time) }}</span>
                                        @else
                                            <span class="cell-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="cell-muted">{{ $production->treatment_duration_days ? $production->treatment_duration_days.' hari' : ($production->is_forever ? 'Selamanya' : $production->duration_days.' hari') }}</td>
                                    <td>
                                        @if ($production->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ route('treatments.show', $production) }}">Detail</a>
                                        <a class="btn btn-sm" href="{{ route('treatments.edit', $production) }}">Edit</a>
                                        <a class="btn btn-sm" href="{{ route('treatments.pdf', $production) }}">PDF</a>
                                        <form method="POST" action="{{ route('treatments.destroy', $production) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus pengobatan?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($productions->isEmpty())
                                <tr><td colspan="9" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data pengobatan.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
