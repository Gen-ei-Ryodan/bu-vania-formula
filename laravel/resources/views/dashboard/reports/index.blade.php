<x-layouts.dashboard title="Laporan" heading="Laporan">
    <div class="panel">
        <div class="panel-header">
            <h2>Tipe Laporan</h2>
            <div style="display: flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('reports.index') }}">Laporan Default</a>
                <a class="btn" href="{{ route('reports.index', ['report_type' => 'history_konsep']) }}">History Konsep per Tanggal</a>
                <a class="btn" href="{{ route('reports.index', ['report_type' => 'konsep_aktif']) }}">Konsep Aktif per Tanggal</a>
            </div>
        </div>
    </div>
    <div class="panel" style="margin-top: 16px;">
        <div class="panel-header">
            <h2>Filter</h2>
        </div>
        <div class="panel-body">
            <form method="GET" action="{{ route('reports.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: end;">
                <div class="field" style="margin: 0; width: 180px;">
                    <div class="label">Nama</div>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama...">
                </div>
                <div class="field" style="margin: 0; width: 160px;">
                    <div class="label">Jenis Produksi</div>
                    <select name="production_type">
                        <option value="">Semua</option>
                        <option value="biasa" @selected(request('production_type') === 'biasa')>Biasa</option>
                        <option value="treatment" @selected(request('production_type') === 'treatment')>Pengobatan</option>
                    </select>
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Tgl Campur Dari</div>
                    <input type="date" name="mix_date_from" value="{{ request('mix_date_from') }}">
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Tgl Campur Sampai</div>
                    <input type="date" name="mix_date_to" value="{{ request('mix_date_to') }}">
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Tgl Mulai Dari</div>
                    <input type="date" name="start_date_from" value="{{ request('start_date_from') }}">
                </div>
                <div class="field" style="margin: 0; width: 150px;">
                    <div class="label">Tgl Mulai Sampai</div>
                    <input type="date" name="start_date_to" value="{{ request('start_date_to') }}">
                </div>
                <div class="field" style="margin: 0; width: 160px;">
                    <div class="label">Lokasi</div>
                    <select name="location">
                        <option value="">Semua</option>
                        @foreach ($locations as $loc)
                            <option value="{{ $loc->name }}" @selected(request('location') === $loc->name)>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" style="margin: 0; width: 160px;">
                    <div class="label">Kandang</div>
                    <input type="text" name="cage" value="{{ request('cage') }}" placeholder="Nama kandang...">
                </div>
                <div style="display: flex; gap: 8px; align-items: end; padding-bottom: 2px;">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    @if (request()->anyFilled(['name', 'production_type', 'mix_date_from', 'mix_date_to', 'start_date_from', 'start_date_to', 'location', 'cage']))
                        <a href="{{ route('reports.index') }}" class="btn">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="panel" style="margin-top: 16px;">
        <div class="panel-header">
            <h2>Hasil Laporan ({{ $productions->count() }})</h2>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('reports.pdf', request()->query()) }}" class="btn btn-primary">Cetak PDF</a>
            </div>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Tanggal Mulai Pakai Konsep</th>
                        <th>Durasi</th>
                        <th>Lokasi</th>
                        <th>Kandang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productions as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->name }}</td>
                            <td><span class="chip">{{ $p->production_type === 'treatment' ? 'Pengobatan' : 'Biasa' }}</span></td>
                            <td>{{ $p->start_date?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ $p->is_forever ? 'Selamanya' : $p->duration_days.' hari' }}</td>
                            <td>{{ $p->location ?? '-' }}</td>
                            <td>{{ $p->cage ?? '-' }}</td>
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
