<x-layouts.dashboard title="Edit Lokasi" heading="Edit Lokasi">
    <div class="page-hero">
        <h1>{{ $location->name }}</h1>
        <p>Edit lokasi dan kelola kandang</p>
    </div>

    <div class="content-section">
        <form method="POST" action="{{ route('locations.update', $location) }}">
            @csrf
            @method('PUT')
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Lokasi</h3></div>
                <div class="form-card-body">
                    <div class="field" style="max-width: 400px;">
                        <div class="label">Nama</div>
                        <input type="text" name="name" value="{{ old('name', $location->name) }}" placeholder="Lokasi A">
                    </div>
                </div>
                <div class="form-card-footer">
                    <a class="btn btn-ghost" href="{{ route('locations.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h3>Kandang di {{ $location->name }}</h3>
                <form method="POST" action="{{ route('locations.cages.store', $location) }}" style="display: flex; gap: 8px; align-items: end;">
                    @csrf
                    <div class="field" style="margin: 0; width: 200px;">
                        <input type="text" name="name" placeholder="Nama Kandang">
                    </div>
                    <button class="btn btn-primary" type="submit">Tambah</button>
                </form>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nama</th>
                                <th style="width: 280px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($location->cages as $cage)
                                <tr>
                                    <td class="cell-muted">#{{ $cage->id }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cages.update', $cage) }}" style="display: flex; gap: 8px; align-items: center;">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $cage->name }}" style="width: 200px;">
                                            <button class="btn btn-sm btn-primary" type="submit">Update</button>
                                        </form>
                                    </td>
                                    <td class="cell-actions">
                                        <form method="POST" action="{{ route('cages.destroy', $cage) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus kandang?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($location->cages->isEmpty())
                                <tr><td colspan="3" style="text-align: center; padding: 32px; color: var(--text-muted);">Belum ada kandang.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
