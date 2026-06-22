<x-layouts.dashboard title="Konsep" heading="Konsep (Resep Dasar)">
    <div class="panel">
        <div class="panel-header">
            <h2>Filter</h2>
        </div>
        <div class="panel-body">
            <form method="GET" action="{{ route('concepts.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: end;">
                <div class="field" style="margin: 0; width: 200px;">
                    <div class="label">Nama Konsep</div>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama...">
                </div>
                <div style="display: flex; gap: 8px; align-items: end; padding-bottom: 2px;">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    @if (request()->anyFilled(['name']))
                        <a href="{{ route('concepts.index') }}" class="btn">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="panel" style="margin-top: 16px;">
        <div class="panel-header">
            <h2>Konsep ({{ $concepts->count() }})</h2>
            <a class="btn btn-primary" href="{{ route('concepts.create') }}">Buat</a>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Berat Dasar (kg)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($concepts as $concept)
                        <tr>
                            <td>{{ $concept->id }}</td>
                            <td>{{ $concept->name }}</td>
                            <td>{{ formatWeight($concept->base_weight_kg) }}</td>
                            <td>
                                <a class="btn" href="{{ route('concepts.show', $concept) }}">Detail</a>
                                <a class="btn" href="{{ route('concepts.edit', $concept) }}">Edit</a>
                                <form method="POST" action="{{ route('concepts.destroy', $concept) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus konsep?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($concepts->isEmpty())
                        <tr>
                            <td colspan="4" class="muted">Belum ada data.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
