<x-layouts.dashboard :title="'Concept: '.$concept->name" :heading="'Concept: '.$concept->name">
    <div class="panel">
        <div class="panel-header">
            <h2>Detail Concept</h2>
            <div class="actions">
                <a class="btn" href="{{ route('concepts.edit', $concept) }}">Edit</a>
                <a class="btn" href="{{ route('concepts.index') }}">Kembali</a>
                <form method="POST" action="{{ route('concepts.destroy', $concept) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Hapus</button>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <div class="grid-2">
                <div class="card">
                    <div class="muted">Nama</div>
                    <strong style="font-size: 18px;">{{ $concept->name }}</strong>
                </div>
                <div class="card">
                    <div class="muted">Base Weight (kg)</div>
                    <strong style="font-size: 18px;">{{ number_format($concept->base_weight_kg, 2) }}</strong>
                </div>
            </div>

            <div class="divider"></div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Weight (kg)</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($concept->items as $row)
                        <tr>
                            <td>{{ $row->item?->name }}</td>
                            <td>{{ number_format($row->weight_kg, 2) }}</td>
                            <td>{{ $row->percentage }}%</td>
                        </tr>
                    @endforeach
                    @if ($concept->items->isEmpty())
                        <tr>
                            <td colspan="3" class="muted">Belum ada item.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
