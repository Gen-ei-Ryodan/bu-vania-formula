<x-layouts.dashboard title="Item" heading="Item">
    <div class="page-hero">
        <h1>Master Item</h1>
        <p>Daftar semua bahan baku, vitamin, dan obat</p>
        <div class="page-hero-actions">
            <a class="btn btn-primary" href="{{ route('items.create') }}">+ Tambah Item</a>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Unit Default</th>
                                <th>Harga</th>
                                <th style="width: 180px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="cell-muted">#{{ $item->id }}</td>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td><span class="badge badge-muted">{{ $item->category?->name }}</span></td>
                                    <td class="cell-muted">{{ $item->defaultUnit?->name }}</td>
                                    <td class="cell-muted">{{ $item->price ? formatCurrency($item->price).' / '.formatWeight($item->price_unit_value).' '.$item->priceUnit?->name : '-' }}</td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ route('items.edit', $item) }}">Edit</a>
                                        <form action="{{ route('items.destroy', $item) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus item?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($items->isEmpty())
                                <tr><td colspan="6" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data item.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
