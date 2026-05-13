<x-layouts.dashboard title="Dashboard" heading="Dashboard">
    <div class="grid-3">
        <div class="card">
            <div class="muted">Units</div>
            <strong>{{ $counts['units'] }}</strong>
        </div>
        <div class="card">
            <div class="muted">Items</div>
            <strong>{{ $counts['items'] }}</strong>
        </div>
        <div class="card">
            <div class="muted">Concepts</div>
            <strong>{{ $counts['concepts'] }}</strong>
        </div>
        <div class="card">
            <div class="muted">Productions</div>
            <strong>{{ $counts['productions'] }}</strong>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="panel-body">
            <div class="actions">
                <a class="btn btn-primary" href="{{ route('units.create') }}">Tambah Unit</a>
                <a class="btn btn-primary" href="{{ route('items.create') }}">Tambah Item</a>
                <a class="btn btn-primary" href="{{ route('concepts.create') }}">Buat Concept</a>
                <a class="btn btn-primary" href="{{ route('productions.create') }}">Buat Production</a>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
