<x-layouts.dashboard title="Dashboard" heading="Dashboard">
    <div class="page-hero">
        <h1>Dashboard</h1>
        <p>Ringkasan sistem produksi pakan — Program Formula</p>
    </div>

    @if (Auth::user()->isAdmin())
    <div class="content-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(37,99,235,.1); color: var(--primary);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                </div>
                <div class="label">Satuan</div>
                <div class="value">{{ $counts['units'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(245,158,11,.1); color: #B45309;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z"/><circle cx="8" cy="14" r="2"/><path d="M12 14v-4"/><path d="M16 14v-2"/></svg>
                </div>
                <div class="label">Kategori</div>
                <div class="value">{{ $counts['categories'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(22,163,74,.1); color: var(--success);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <div class="label">Item</div>
                <div class="value">{{ $counts['items'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(139,92,246,.1); color: #7C3AED;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="label">Konsep</div>
                <div class="value">{{ $counts['concepts'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(8,145,178,.1); color: var(--info);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                </div>
                <div class="label">Produksi</div>
                <div class="value">{{ $counts['productions'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(220,38,38,.1); color: var(--danger);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div class="label">Pengobatan</div>
                <div class="value">{{ $counts['treatments'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(37,99,235,.1); color: var(--primary);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="label">Laporan Sore</div>
                <div class="value">{{ $counts['laporan_sore'] }}</div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h3>Aksi Cepat</h3>
            </div>
            <div class="card-body">
                <div class="inline">
                    <a class="btn btn-primary" href="{{ route('units.create') }}">+ Tambah Unit</a>
                    <a class="btn" href="{{ route('categories.index') }}">Kelola Kategori</a>
                    <a class="btn" href="{{ route('items.create') }}">+ Tambah Item</a>
                    <a class="btn" href="{{ route('concepts.create') }}">+ Buat Konsep</a>
                    <a class="btn" href="{{ route('productions.create') }}">+ Buat Produksi</a>
                    <a class="btn" href="{{ route('treatments.create') }}">+ Buat Pengobatan</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h3>Laporan Sore Terbaru</h3>
                <a class="btn btn-primary btn-sm" href="{{ route('laporan-sore.create') }}">+ Buat Laporan</a>
            </div>
            <div class="card-body" style="padding: 0;">
                @php
                    $recentLaporans = \App\Models\LaporanSore::with('location')->latest()->take(5)->get();
                @endphp
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentLaporans as $laporan)
                                <tr>
                                    <td>{{ $laporan->tanggal->format('d-m-Y') }}</td>
                                    <td class="cell-muted">{{ $laporan->location?->name }}</td>
                                    <td class="cell-actions"><a class="btn btn-sm" href="{{ route('laporan-sore.show', $laporan) }}">Detail</a></td>
                                </tr>
                            @endforeach
                            @if ($recentLaporans->isEmpty())
                                <tr><td colspan="3" style="text-align: center; padding: 40px; color: var(--text-muted);">Belum ada laporan sore.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
