<x-layouts.dashboard title="Dashboard" heading="Dashboard">
    <div class="grid-3">
        <div class="card">
            <div class="muted">Unit</div>
            <strong>{{ $counts['units'] }}</strong>
        </div>
        <div class="card">
            <div class="muted">Item</div>
            <strong>{{ $counts['items'] }}</strong>
        </div>
        <div class="card">
            <div class="muted">Konsep</div>
            <strong>{{ $counts['concepts'] }}</strong>
        </div>
        <div class="card">
            <div class="muted">Produksi</div>
            <strong>{{ $counts['productions'] }}</strong>
        </div>
        <div class="card">
            <div class="muted">Pengobatan</div>
            <strong>{{ $counts['treatments'] }}</strong>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Aksi Cepat</h2>
        </div>
        <div class="panel-body">
            <div class="actions">
                <a class="btn btn-primary" href="{{ route('units.create') }}">Tambah Unit</a>
                <a class="btn btn-primary" href="{{ route('items.create') }}">Tambah Item</a>
                <a class="btn btn-primary" href="{{ route('concepts.create') }}">Buat Konsep</a>
                <a class="btn btn-primary" href="{{ route('productions.create') }}">Buat Produksi</a>
                <a class="btn btn-primary" href="{{ route('treatments.create') }}">Buat Pengobatan</a>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
