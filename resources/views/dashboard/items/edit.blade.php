<x-layouts.dashboard title="Edit Item" heading="Edit Item">
    <div class="page-hero">
        <h1>Edit Item</h1>
        <p>{{ $item->name }}</p>
    </div>
    <div class="content-section">
        <form method="POST" action="{{ route('items.update', $item) }}">
            @csrf
            @method('PUT')
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Item</h3></div>
                <div class="form-card-body">
                    @include('dashboard.items._form', ['item' => $item])
                </div>
                <div class="form-card-footer">
                    <a class="btn btn-ghost" href="{{ route('items.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
