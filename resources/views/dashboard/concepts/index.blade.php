<x-layouts.dashboard title="Concepts" heading="Concepts (Resep Dasar)">
    <div class="panel">
        <div class="panel-header">
            <h2>Concepts</h2>
            <a class="btn btn-primary" href="{{ route('concepts.create') }}">Buat</a>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Base Weight (gram)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($concepts as $concept)
                        <tr>
                            <td>{{ $concept->id }}</td>
                            <td>{{ $concept->name }}</td>
                            <td>{{ number_format($concept->base_weight_kg, 2) }}</td>
                            <td>
                                <a class="btn" href="{{ route('concepts.show', $concept) }}">Detail</a>
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
