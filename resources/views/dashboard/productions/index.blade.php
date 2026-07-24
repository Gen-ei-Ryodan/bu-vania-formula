<x-layouts.dashboard title="Produksi" heading="Produksi">
    <div class="page-hero">
        <h1>Produksi</h1>
        <p>Daftar produksi pakan ternak</p>
        <div class="page-hero-actions">
            <a class="btn btn-primary" href="{{ route('productions.create') }}">+ Buat Produksi</a>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-body">
                <div class="toolbar">
                    <form method="GET" action="{{ route('productions.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: end; width: 100%;">
                        <div class="field" style="margin: 0; flex: 1; min-width: 160px;">
                            <div class="label">Nama</div>
                            <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama...">
                        </div>
                        <div class="field" style="margin: 0; flex: 1; min-width: 140px;">
                            <div class="label">Lokasi</div>
                            <input type="text" name="location" value="{{ request('location') }}" placeholder="Lokasi...">
                        </div>
                        <div class="field" style="margin: 0; width: 130px;">
                            <div class="label">Tgl Mulai Dari</div>
                            <input type="date" name="start_date_from" value="{{ request('start_date_from') }}">
                        </div>
                        <div class="field" style="margin: 0; width: 130px;">
                            <div class="label">Sampai</div>
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
                        <div class="toolbar-actions" style="padding-bottom: 6px;">
                            <button class="btn btn-primary btn-sm" type="submit">Cari</button>
                            @if (request()->anyFilled(['name', 'location', 'cage', 'start_date_from', 'start_date_to', 'is_active']))
                                <a href="{{ route('productions.index') }}" class="btn btn-sm">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h3>Produksi ({{ $productions->count() }})</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Kandang</th>
                                <th>Mulai Pakai Konsep</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th style="width: 240px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productions as $production)
                                <tr>
                                    <td class="cell-muted">#{{ $production->id }}</td>
                                    <td><strong>{{ $production->name }}</strong></td>
                                    <td class="cell-muted">{{ $production->location ?? '-' }}</td>
                                    <td class="cell-muted">{{ $production->cage ?? '-' }}</td>
                                    <td class="cell-muted">{{ $production->start_date?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="cell-muted">{{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</td>
                                    <td>
                                        @if ($production->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ route('productions.show', $production) }}">Detail</a>
                                        <a class="btn btn-sm" href="{{ route('productions.edit', $production) }}">Edit</a>
                                        <a class="btn btn-sm" href="{{ route('productions.excel', $production) }}" style="background:#059669;border-color:#059669;color:#fff;">Excel</a>
                                        <div x-data="{ open: false }" style="position:relative;display:inline-block;">
                                            <button class="btn btn-sm" @click="open = !open; if(open) $nextTick(() => { let r = $el.getBoundingClientRect(); $refs.menu.style.top = (r.bottom + 4) + 'px'; $refs.menu.style.left = (r.left) + 'px' })" @click.outside="open = false" type="button">PDF &#9660;</button>
                                            <div x-show="open" x-cloak x-ref="menu" style="position:fixed;z-index:9999;background:#fff;border:1px solid var(--border,#E2E8F0);border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);min-width:130px;padding:4px;">
                                                <a href="{{ route('productions.pdf', [$production, 'cards' => 2]) }}" style="display:block;padding:5px 10px;border-radius:6px;text-decoration:none;color:var(--text,#0F172A);font-size:12px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background=''">2 Copy</a>
                                                <a href="{{ route('productions.pdf', [$production, 'cards' => 4]) }}" style="display:block;padding:5px 10px;border-radius:6px;text-decoration:none;color:var(--text,#0F172A);font-size:12px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background=''">4 Copy</a>
                                                <a href="{{ route('productions.pdf', [$production, 'cards' => 6]) }}" style="display:block;padding:5px 10px;border-radius:6px;text-decoration:none;color:var(--text,#0F172A);font-size:12px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background=''">6 Copy</a>
                                                <a href="{{ route('productions.pdf', [$production, 'cards' => 9]) }}" style="display:block;padding:5px 10px;border-radius:6px;text-decoration:none;color:var(--text,#0F172A);font-size:12px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background=''">9 Copy</a>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('productions.destroy', $production) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus produksi?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($productions->isEmpty())
                                <tr><td colspan="8" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data produksi.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
