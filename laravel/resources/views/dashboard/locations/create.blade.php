<x-layouts.dashboard title="Tambah Lokasi" heading="Tambah Lokasi">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Lokasi</h2>
            <a class="btn" href="{{ route('locations.index') }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('locations.store') }}">
                @csrf
                <div class="field">
                    <div class="label">Nama</div>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Lokasi A">
                </div>
                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
