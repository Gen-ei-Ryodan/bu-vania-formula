<x-layouts.dashboard title="Produksi" heading="Produksi">
    <div class="panel">
        <div class="panel-header">
            <h2>Filter</h2>
        </div>
        <div class="panel-body">
            <form method="GET" action="{{ route('productions.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: end;">
                <div class="field" style="margin: 0; width: 180px;">
                    <div class="label">Nama</div>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama...">
                </div>
                <div class="field" style="margin: 0; width: 180px;">
                    <div class="label">Lokasi</div>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="Nama lokasi...">
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Kandang</div>
                    <input type="text" name="cage" value="{{ request('cage') }}" placeholder="Nama kandang...">
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Tgl Mulai Dari</div>
                    <input type="date" name="start_date_from" value="{{ request('start_date_from') }}">
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Tgl Mulai Sampai</div>
                    <input type="date" name="start_date_to" value="{{ request('start_date_to') }}">
                </div>
                <div class="field" style="margin: 0; width: 120px;">
                    <div class="label">Status</div>
                    <select name="is_active">
                        <option value="">Semua</option>
                        <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                        <option value="0" @selected(request('is_active') === '0')>Tidak Aktif</option>
                    </select>
                </div>
                <div style="display: flex; gap: 8px; align-items: end; padding-bottom: 2px;">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    @if (request()->anyFilled(['name', 'location', 'cage', 'start_date_from', 'start_date_to', 'is_active']))
                        <a href="{{ route('productions.index') }}" class="btn">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="panel" style="margin-top: 16px;">
        <div class="panel-header">
            <h2>Produksi ({{ $productions->count() }})</h2>
            <a class="btn btn-primary" href="{{ route('productions.create') }}">Buat</a>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Kandang</th>
                        <th>Tanggal Mulai Pakai Konsep</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productions as $production)
                        <tr>
                            <td>{{ $production->id }}</td>
                            <td>{{ $production->name }}</td>
                            <td>{{ $production->location ?? '-' }}</td>
                            <td>{{ $production->cage ?? '-' }}</td>
                            <td>{{ $production->start_date?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</td>
                            <td><span class="chip" style="{{ $production->is_active ? 'background: #d1fae5; color: #065f46;' : 'background: #fee2e2; color: #991b1b;' }}">{{ $production->is_active ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                            <td>
                                <a class="btn" href="{{ route('productions.show', $production) }}">Detail</a>
                                <a class="btn" href="{{ route('productions.edit', $production) }}">Edit</a>
                                <a class="btn" href="{{ route('productions.pdf', $production) }}">PDF</a>
                                <form method="POST" action="{{ route('productions.destroy', $production) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus produksi?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($productions->isEmpty())
                        <tr>
                            <td colspan="8" class="muted">Belum ada data.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
