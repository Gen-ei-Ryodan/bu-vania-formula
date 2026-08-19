<x-layouts.dashboard title="Edit Satuan" heading="Edit Satuan">
    <div class="page-hero">
        <h1>Edit Satuan</h1>
        <p>{{ $unit->name }}</p>
    </div>
    <div class="content-section">
        <form method="POST" action="{{ route('units.update', $unit) }}">
            @csrf
            @method('PUT')
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Satuan</h3></div>
                <div class="form-card-body">
                    <div class="form-grid">
                        <div class="field">
                            <div class="label">Nama</div>
                            <input type="text" name="name" value="{{ old('name', $unit->name) }}">
                        </div>
                        <div class="field">
                            <div class="label">Dimensi</div>
                            <select name="dimension">
                                <option value="mass" @selected(old('dimension', $unit->dimension) === 'mass')>Massa</option>
                                <option value="volume" @selected(old('dimension', $unit->dimension) === 'volume')>Volume</option>
                            </select>
                        </div>
                        <div class="field">
                            <div class="label">Konversi ke unit dasar dimensi</div>
                            <input type="number" step="0.0001" name="conversion_to_kg" value="{{ old('conversion_to_kg', $unit->conversion_to_kg) }}">
                        </div>
                    </div>
                </div>
                <div class="form-card-footer">
                    <a class="btn btn-ghost" href="{{ route('units.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
