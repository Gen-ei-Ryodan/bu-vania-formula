<x-layouts.dashboard title="Units" heading="Master Units">
    <div class="panel">
        <div class="panel-header">
            <h2>Units</h2>
            <a class="btn btn-primary" href="{{ route('units.create') }}">Tambah</a>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Konversi ke gram</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ number_format($unit->conversion_to_gram) }}</td>
                            <td>
                                <div class="actions">
                                    <a class="btn" href="{{ route('units.edit', $unit) }}">Edit</a>
                                    <form action="{{ route('units.destroy', $unit) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($units->isEmpty())
                        <tr>
                            <td colspan="4" class="muted">Belum ada data.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
