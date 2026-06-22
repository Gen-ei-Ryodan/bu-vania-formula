<x-layouts.dashboard title="Laporan Sore" heading="Laporan Sore">
    <div class="page-hero">
        <h1>Laporan Sore</h1>
        <p>Laporan kondisi dan aktivitas harian</p>
        <div class="page-hero-actions">
            <a class="btn btn-primary" href="{{ route('laporan-sore.create') }}">+ Buat Laporan</a>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Dibuat Oleh</th>
                                <th style="width: 180px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporans as $laporan)
                                <tr>
                                    <td class="cell-muted">#{{ $laporan->id }}</td>
                                    <td><strong>{{ $laporan->tanggal->format('d-m-Y') }}</strong></td>
                                    <td class="cell-muted">{{ $laporan->location?->name }}</td>
                                    <td class="cell-muted">{{ $laporan->user?->name }}</td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ route('laporan-sore.show', $laporan) }}">Detail</a>
                                        <form method="POST" action="{{ route('laporan-sore.destroy', $laporan) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus laporan?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($laporans->isEmpty())
                                <tr><td colspan="5" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada laporan sore.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
