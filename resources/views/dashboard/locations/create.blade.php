<x-layouts.dashboard title="Tambah Lokasi" heading="Tambah Lokasi">
    <div class="page-hero">
        <h1>Tambah Lokasi Baru</h1>
        <p>Buat lokasi baru untuk kandang ternak</p>
    </div>
    <div class="content-section">
        <form method="POST" action="{{ route('locations.store') }}">
            @csrf
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Lokasi</h3></div>
                <div class="form-card-body">
                    <div class="field" style="max-width: 400px;">
                        <div class="label">Nama</div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Lokasi A">
                    </div>
                </div>
                <div class="form-card-footer">
                    <a class="btn btn-ghost" href="{{ route('locations.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
