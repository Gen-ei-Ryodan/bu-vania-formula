<x-layouts.dashboard title="Konsep" heading="Konsep (Resep Dasar)">
    <div class="panel">
        <div class="panel-header">
            <h2>Konsep</h2>
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
                                    <button class="btn btn-danger" type="submit">Hapus</button>
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
