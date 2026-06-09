<x-layouts.dashboard :title="$title" heading="{{ $title }}">
    <div class="panel">
        <div class="panel-header">
            <h2>Laporan</h2>
            <div style="display: flex; gap: 8px;">
                <a class="btn" href="{{ route('reports.index') }}">Laporan Default</a>
                <a class="btn btn-primary" href="{{ route('reports.index', ['report_type' => 'history_konsep']) }}">History Konsep per Tanggal</a>
                <a class="btn btn-primary" href="{{ route('reports.index', ['report_type' => 'konsep_aktif']) }}">Konsep Aktif per Tanggal</a>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-top: 16px;">
        <div class="panel-header">
            <h2>{{ $title }}</h2>
        </div>
        <div class="panel-body">
            <form method="GET" action="{{ route('reports.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: end;">
                <input type="hidden" name="report_type" value="{{ $type }}">
                <div class="field" style="margin: 0; width: 180px;">
                    <div class="label">Nama Produksi</div>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama...">
                </div>
                <div class="field" style="margin: 0; width: 180px;">
                    <div class="label">Konsep</div>
                    <select name="concept_id">
                        <option value="">Semua</option>
                        @foreach ($concepts as $c)
                            <option value="{{ $c->id }}" @selected(request('concept_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Tgl Mulai Dari</div>
                    <input type="date" name="start_date_from" value="{{ request('start_date_from') }}">
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Tgl Mulai Sampai</div>
                    <input type="date" name="start_date_to" value="{{ request('start_date_to') }}">
                </div>
                <div style="display: flex; gap: 8px; align-items: end; padding-bottom: 2px;">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('reports.pdf', ['report_type' => $type] + request()->only(['name', 'concept_id', 'start_date_from', 'start_date_to'])) }}" class="btn btn-primary">Cetak PDF</a>
                    @if (request()->anyFilled(['name', 'concept_id', 'start_date_from', 'start_date_to']))
                        <a href="{{ route('reports.index', ['report_type' => $type]) }}" class="btn">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="panel" style="margin-top: 16px;">
        <div class="panel-header">
            <h2>Hasil ({{ $productions->count() }})</h2>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <th>Nama Produksi</th>
                        <th>Konsep</th>
                        <th>Jenis</th>
                        <th>Kapasitas (kg)</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productions as $p)
                        <tr>
                            <td>{{ $p->start_date?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->concept?->name }}</td>
                            <td><span class="chip">{{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</span></td>
                            <td>{{ formatWeight($p->target_weight_kg) }} kg</td>
                            <td>{{ $p->location ?? '-' }}</td>
                            <td><span class="chip" style="{{ $p->is_active ? 'background: #d1fae5; color: #065f46;' : 'background: #fee2e2; color: #991b1b;' }}">{{ $p->is_active ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                            <td>
                                <a class="btn" href="{{ $p->production_type === 'treatment' ? route('treatments.show', $p) : route('productions.show', $p) }}" style="font-size: 12px; padding: 3px 8px;">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                    @if ($productions->isEmpty())
                        <tr><td colspan="8" class="muted">Tidak ada data.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
