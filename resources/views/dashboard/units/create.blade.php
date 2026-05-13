<x-layouts.dashboard title="Tambah Unit" heading="Tambah Unit">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Unit</h2>
            <a class="btn" href="{{ route('units.index') }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('units.store') }}">
                @csrf
                <div class="grid-2">
                    <div class="field">
                        <div class="label">Nama</div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="kg / ton / sak / gram">
                    </div>
                    <div class="field">
                        <div class="label">Konversi ke kg</div>
                        <input type="number" step="0.0001" name="conversion_to_kg" value="{{ old('conversion_to_kg') }}" placeholder="1 untuk kg">
                    </div>
                </div>
                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
