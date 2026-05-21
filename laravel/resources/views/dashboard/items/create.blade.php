<x-layouts.dashboard title="Tambah Item" heading="Tambah Item">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Item</h2>
            <a class="btn" href="{{ route('items.index') }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('items.store') }}">
                @csrf
                @include('dashboard.items._form', ['item' => new \App\Models\Item()])
                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
