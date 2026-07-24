<x-layouts.dashboard title="Laporan" heading="Laporan">
    <div class="page-hero">
        <h1>Laporan</h1>
        <p>Cetak laporan produksi dan konsep</p>
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
                        <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                        <div class="field" style="margin: 0; flex: 1; min-width: 160px;">
                            <div class="label">Nama</div>
                            <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama...">
                        </div>
                        @if (request('report_type') !== 'history_konsep' && request('report_type') !== 'konsep_aktif')
                        <div class="field" style="margin: 0; width: 140px;">
                            <div class="label">Jenis</div>
                            <select name="production_type">
                                <option value="">Semua</option>
                                <option value="biasa" @selected(request('production_type') === 'biasa')>Biasa</option>
                                <option value="treatment" @selected(request('production_type') === 'treatment')>Pengobatan</option>
                            </select>
                        </div>
                        @endif
                        <div class="field" style="margin: 0; width: 130px;">
                            <div class="label">Tgl Mulai Dari</div>
                            <input type="date" name="start_date_from" value="{{ request('start_date_from') }}">
                        </div>
                        <div class="field" style="margin: 0; width: 130px;">
                            <div class="label">Sampai</div>
                            <input type="date" name="start_date_to" value="{{ request('start_date_to') }}">
                        </div>
                        @if (request('report_type') !== 'history_konsep' && request('report_type') !== 'konsep_aktif')
                        <div class="field" style="margin: 0; flex: 1; min-width: 140px;">
                            <div class="label">Lokasi</div>
                            <select name="location">
                                <option value="">Semua Lokasi</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->name }}" @selected(request('location') === $loc->name)>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="toolbar-actions" style="padding-bottom: 6px;">
                            <button class="btn btn-primary btn-sm" type="submit">Cari</button>
                            @if (request()->anyFilled(['name', 'production_type', 'start_date_from', 'start_date_to', 'location']))
                                <a href="{{ route('reports.index') }}" class="btn btn-sm">Reset</a>
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
                <a href="{{ route('reports.pdf', request()->query()) }}" class="btn btn-primary btn-sm">Cetak PDF</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Tanggal Mulai Pakai Konsep</th>
                                <th>Durasi</th>
                                <th>Lokasi</th>
                                <th>Kandang</th>
                                <th style="width: 100px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productions as $i => $p)
                                <tr>
                                    <td class="cell-muted">{{ $i + 1 }}</td>
                                    <td><strong>{{ $p->name }}</strong></td>
                                    <td><span class="badge badge-muted">{{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</span></td>
                                    <td class="cell-muted">{{ $p->start_date?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="cell-muted">{{ $p->is_forever ? 'Selamanya' : $p->duration_days.' hari' }}</td>
                                    <td class="cell-muted">{{ $p->location ?? '-' }}</td>
                                    <td class="cell-muted">{{ $p->cage ?? '-' }}</td>
                                    <td class="cell-actions">
                                        <a class="btn btn-sm" href="{{ $p->production_type === 'treatment' ? route('treatments.show', $p) : route('productions.show', $p) }}">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($productions->isEmpty())
                                <tr><td colspan="8" style="text-align: center; padding: 48px; color: var(--text-muted);">Tidak ada data.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
