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
                        <div class="label">Konversi ke gram</div>
                        <input type="number" name="conversion_to_gram" value="{{ old('conversion_to_gram') }}" placeholder="1000 untuk kg">
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
