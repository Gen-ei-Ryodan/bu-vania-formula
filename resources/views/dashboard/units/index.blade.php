<x-layouts.dashboard title="Satuan" heading="Satuan">
    <div class="page-hero">
        <h1>Master Satuan</h1>
        <p>Unit satuan untuk item dan konsep</p>
        <div class="page-hero-actions">
            <a class="btn btn-primary" href="{{ route('units.create') }}">+ Tambah Satuan</a>
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
                                <th>Konversi ke kg</th>
                                <th style="width: 180px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                                <tr>
                                    <td class="cell-muted">#{{ $unit->id }}</td>
                                    <td><strong>{{ $unit->name }}</strong></td>
                                    <td class="cell-muted">{{ formatWeight($unit->conversion_to_kg) }}</td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ route('units.edit', $unit) }}">Edit</a>
                                        <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($units->isEmpty())
                                <tr><td colspan="4" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data satuan.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
