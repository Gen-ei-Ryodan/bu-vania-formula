<x-layouts.dashboard title="Tambah Item" heading="Tambah Item">
    <div class="page-hero">
        <h1>Tambah Item Baru</h1>
        <p>Buat item bahan baku, vitamin, atau obat</p>
    </div>
    <div class="content-section">
        <form method="POST" action="{{ route('items.store') }}">
            @csrf
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Item</h3></div>
                <div class="form-card-body">
                    @include('dashboard.items._form', ['item' => new \App\Models\Item()])
                </div>
                <div class="form-card-footer">
                    <a class="btn btn-ghost" href="{{ route('items.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
