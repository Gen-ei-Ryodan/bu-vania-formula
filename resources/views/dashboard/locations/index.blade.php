<x-layouts.dashboard title="Lokasi" heading="Lokasi">
    <div class="panel">
        <div class="panel-header">
            <h2>Master Lokasi</h2>
            <a class="btn btn-primary" href="{{ route('locations.create') }}">Tambah</a>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jumlah Kandang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $location)
                        <tr>
                            <td>{{ $location->id }}</td>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->cages_count }}</td>
                            <td>
                                <a class="btn" href="{{ route('locations.edit', $location) }}">Edit</a>
                                <form method="POST" action="{{ route('locations.destroy', $location) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($locations->isEmpty())
                        <tr><td colspan="4" class="muted">Belum ada data.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
