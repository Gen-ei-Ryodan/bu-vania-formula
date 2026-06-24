<x-layouts.dashboard :title="$title" heading="{{ $title }}">
    <div class="page-hero">
        <h1>Laporan Konsep</h1>
        <p>{{ $title }}</p>
        <div class="page-hero-actions">
            <a class="btn" href="{{ route('reports.index') }}">Laporan Default</a>
            <a class="btn" href="{{ route('reports.index', ['report_type' => 'history_konsep']) }}">History Konsep</a>
            <a class="btn" href="{{ route('reports.index', ['report_type' => 'konsep_aktif']) }}">Konsep Aktif</a>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-body">
                <div class="toolbar">
                    <form method="GET" action="{{ route('reports.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: end; width: 100%;">
                        <input type="hidden" name="report_type" value="{{ $type }}">
                        <div class="field" style="margin: 0; flex: 1; min-width: 140px;">
                            <div class="label">Nama Produksi</div>
                            <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama...">
                        </div>
                        <div class="field" style="margin: 0; flex: 1; min-width: 140px;">
                            <div class="label">Konsep</div>
                            <select name="concept_id">
                                <option value="">Semua</option>
                                @foreach ($concepts as $c)
                                    <option value="{{ $c->id }}" @selected(request('concept_id') == $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field" style="margin: 0; width: 140px;">
                            <div class="label">Tgl Mulai Dari</div>
                            <input type="date" name="start_date_from" value="{{ request('start_date_from') }}">
                        </div>
                        <div class="field" style="margin: 0; width: 140px;">
                            <div class="label">Sampai</div>
                            <input type="date" name="start_date_to" value="{{ request('start_date_to') }}">
                        </div>
                        <div class="toolbar-actions" style="padding-bottom: 6px;">
                            <button class="btn btn-primary btn-sm" type="submit">Cari</button>
                            <a href="{{ route('reports.pdf', ['report_type' => $type] + request()->only(['name', 'concept_id', 'start_date_from', 'start_date_to'])) }}" class="btn btn-sm btn-primary">Cetak PDF</a>
                            @if (request()->anyFilled(['name', 'concept_id', 'start_date_from', 'start_date_to']))
                                <a href="{{ route('reports.index', ['report_type' => $type]) }}" class="btn btn-sm">Reset</a>
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
                <h3>Hasil ({{ $productions->count() }})</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Tanggal Mulai</th>
                                <th>Nama Produksi</th>
                                <th>Konsep</th>
                                <th>Jenis</th>
                                <th>Kapasitas</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th style="width: 100px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productions as $i => $p)
                                <tr>
                                    <td class="cell-muted">{{ $i + 1 }}</td>
                                    <td class="cell-muted">{{ $p->start_date?->format('d-m-Y') ?? '-' }}</td>
                                    <td><strong>{{ $p->name }}</strong></td>
                                    <td class="cell-muted">{{ $p->concept?->name ?? '-' }}</td>
                                    <td><span class="badge badge-muted">{{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</span></td>
                                    <td class="cell-muted">{{ formatWeight($p->target_weight_kg) }} kg</td>
                                    <td class="cell-muted">{{ $p->location ?? '-' }}</td>
                                    <td>
                                        @if ($p->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ $p->production_type === 'treatment' ? route('treatments.show', $p) : route('productions.show', $p) }}">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($productions->isEmpty())
                                <tr><td colspan="9" style="text-align: center; padding: 48px; color: var(--text-muted);">Tidak ada data.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
