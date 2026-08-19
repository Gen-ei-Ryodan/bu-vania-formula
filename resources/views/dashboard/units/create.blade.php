<x-layouts.dashboard title="Tambah Satuan" heading="Tambah Satuan">
    <div class="page-hero">
        <h1>Tambah Satuan</h1>
        <p>Buat satuan baru (kg, ton, sak, gram, dll)</p>
    </div>
    <div class="content-section">
        <form method="POST" action="{{ route('units.store') }}">
            @csrf
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Satuan</h3></div>
                <div class="form-card-body">
                    <div class="form-grid">
                        <div class="field">
                            <div class="label">Nama</div>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="kg / ton / sak / gram">
                        </div>
                        <div class="field">
                            <div class="label">Dimensi</div>
                            <select name="dimension">
                                <option value="mass" @selected(old('dimension', 'mass') === 'mass')>Massa</option>
                                <option value="volume" @selected(old('dimension') === 'volume')>Volume</option>
                            </select>
                        </div>
                        <div class="field">
                            <div class="label">Konversi ke unit dasar dimensi</div>
                            <input type="number" step="0.0001" name="conversion_to_kg" value="{{ old('conversion_to_kg') }}" placeholder="1 untuk kg">
                        </div>
                    </div>
                </div>
                <div class="form-card-footer">
                    <a class="btn btn-ghost" href="{{ route('units.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
