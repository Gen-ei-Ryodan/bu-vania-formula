<x-layouts.dashboard title="Lokasi" heading="Lokasi">
    <div class="page-hero">
        <h1>Master Lokasi</h1>
        <p>Daftar semua lokasi dan kandang</p>
        <div class="page-hero-actions">
            <a class="btn btn-primary" href="{{ route('locations.create') }}">+ Tambah Lokasi</a>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nama</th>
                                <th>Jumlah Kandang</th>
                                <th style="width: 180px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locations as $location)
                                <tr>
                                    <td class="cell-muted">#{{ $location->id }}</td>
                                    <td><strong>{{ $location->name }}</strong></td>
                                    <td class="cell-muted">{{ $location->cages_count }}</td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ route('locations.edit', $location) }}">Edit</a>
                                        <form method="POST" action="{{ route('locations.destroy', $location) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus lokasi?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($locations->isEmpty())
                                <tr><td colspan="4" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data lokasi.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
