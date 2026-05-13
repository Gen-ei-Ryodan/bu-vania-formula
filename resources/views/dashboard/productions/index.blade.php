<x-layouts.dashboard title="Productions" heading="Productions">
    <div class="panel">
        <div class="panel-header">
            <h2>Productions</h2>
            <a class="btn btn-primary" href="{{ route('productions.create') }}">Buat</a>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Nama Bibit</th>
                        <th>Concept</th>
                        <th>Target (gram)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productions as $production)
                        <tr>
                            <td>{{ $production->id }}</td>
                            <td>{{ $production->name }}</td>
                            <td>{{ $production->seed_name }}</td>
                            <td>{{ $production->concept?->name }}</td>
                            <td>{{ number_format($production->target_weight_gram) }}</td>
                            <td>
                                <a class="btn" href="{{ route('productions.show', $production) }}">Detail</a>
                                <a class="btn" href="{{ route('productions.pdf', $production) }}">PDF</a>
                                <form method="POST" action="{{ route('productions.destroy', $production) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($productions->isEmpty())
                        <tr>
                            <td colspan="6" class="muted">Belum ada data.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
