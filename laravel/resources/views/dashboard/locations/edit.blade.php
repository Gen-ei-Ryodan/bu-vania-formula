<x-layouts.dashboard title="Edit Lokasi" heading="Edit Lokasi">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Lokasi</h2>
            <a class="btn" href="{{ route('locations.index') }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('locations.update', $location) }}">
                @csrf
                @method('PUT')
                <div class="field">
                    <div class="label">Nama</div>
                    <input type="text" name="name" value="{{ old('name', $location->name) }}" placeholder="Lokasi A">
                </div>
                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div class="panel" style="margin-top: 20px;">
        <div class="panel-header">
            <h2>Kandang di {{ $location->name }}</h2>
            <form method="POST" action="{{ route('locations.cages.store', $location) }}" style="display: flex; gap: 8px; align-items: end;">
                @csrf
                <div class="field" style="margin: 0;">
                    <input type="text" name="name" placeholder="Nama Kandang" style="width: 200px;">
                </div>
                <button class="btn btn-primary" type="submit">Tambah</button>
            </form>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($location->cages as $cage)
                        <tr>
                            <td>{{ $cage->id }}</td>
                            <td>
                                <form method="POST" action="{{ route('cages.update', $cage) }}" style="display: inline-flex; gap: 8px; align-items: center;">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $cage->name }}" style="width: 200px;">
                                    <button class="btn" type="submit" style="font-size: 12px; padding: 4px 10px;">Update</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('cages.destroy', $cage) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" style="font-size: 12px; padding: 4px 10px;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($location->cages->isEmpty())
                        <tr><td colspan="3" class="muted">Belum ada kandang.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
