<x-layouts.dashboard title="Edit Item" heading="Edit Item">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Item</h2>
            <a class="btn" href="{{ route('items.index') }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('items.update', $item) }}">
                @csrf
                @method('PUT')
                @include('dashboard.items._form', ['item' => $item])
                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
