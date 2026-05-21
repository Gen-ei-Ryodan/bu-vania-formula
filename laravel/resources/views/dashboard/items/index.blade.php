<x-layouts.dashboard title="Item" heading="Master Item">
    <div class="panel">
        <div class="panel-header">
            <h2>Item</h2>
            <a class="btn btn-primary" href="{{ route('items.create') }}">Tambah</a>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Unit Default</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td><span class="chip">{{ $item->category }}</span></td>
                            <td>{{ $item->defaultUnit?->name }}</td>
                            <td>
                                <div class="actions">
                                    <a class="btn" href="{{ route('items.edit', $item) }}">Edit</a>
                                    <form action="{{ route('items.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($items->isEmpty())
                        <tr>
                            <td colspan="5" class="muted">Belum ada data.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
