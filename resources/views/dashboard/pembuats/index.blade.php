<x-layouts.dashboard title="Konsep Dari" heading="Konsep Dari">
    <div class="page-hero">
        <h1>Master Konsep Dari</h1>
        <p>Daftar pembuat resep konsep</p>
        <div class="page-hero-actions">
            <a class="btn btn-primary" href="{{ route('pembuats.create') }}">+ Tambah Konsep Dari</a>
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
                                <th style="width: 180px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembuats as $pembuat)
                                <tr>
                                    <td class="cell-muted">#{{ $pembuat->id }}</td>
                                    <td><strong>{{ $pembuat->name }}</strong></td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ route('pembuats.edit', $pembuat) }}">Edit</a>
                                        <form action="{{ route('pembuats.destroy', $pembuat) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($pembuats->isEmpty())
                                <tr><td colspan="3" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
