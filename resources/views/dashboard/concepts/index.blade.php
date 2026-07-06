<x-layouts.dashboard title="Konsep" heading="Konsep">
    <div class="page-hero">
        <h1>Konsep (Resep Dasar)</h1>
        <p>Daftar semua resep pakan ternak</p>
        <div class="page-hero-actions">
            <a class="btn btn-primary" href="{{ route('concepts.create') }}">+ Buat Konsep</a>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-body">
                <div class="toolbar">
                    <div class="field" style="min-width: 240px;">
                        <div class="label">Cari Nama Konsep</div>
                        <form method="GET" action="{{ route('concepts.index') }}" style="display: flex; gap: 8px;">
                            <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama konsep..." style="flex: 1;">
                            <button class="btn btn-primary" type="submit">Cari</button>
                            @if (request()->anyFilled(['name']))
                                <a href="{{ route('concepts.index') }}" class="btn">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h3>Konsep ({{ $concepts->count() }})</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nama</th>
                                <th>Konsep Dari</th>
                                <th style="width: 130px;">Berat Dasar</th>
                                <th style="width: 200px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($concepts as $concept)
                                <tr>
                                    <td class="cell-muted">#{{ $concept->id }}</td>
                                    <td><strong>{{ $concept->name }}</strong></td>
                                    <td class="cell-muted">{{ $concept->pembuat?->name ?? '-' }}</td>
                                    <td>{{ formatWeight($concept->base_weight_kg) }} kg</td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ route('concepts.show', $concept) }}">Detail</a>
                                        <a class="btn btn-sm" href="{{ route('concepts.edit', $concept) }}">Edit</a>
                                        <form method="POST" action="{{ route('concepts.destroy', $concept) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus konsep?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($concepts->isEmpty())
                                <tr><td colspan="5" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data konsep.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
