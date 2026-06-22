<x-layouts.dashboard :title="'Pengobatan: '.$production->name" :heading="$production->name">
    {{-- ============================================ --}}
    {{-- SECTION 1: PAGE HERO --}}
    {{-- ============================================ --}}
    <div class="detail-hero">
        <div class="detail-hero-breadcrumb">
            <a href="{{ route('treatments.index') }}" class="breadcrumb-link">Pengobatan</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>Detail Pengobatan</span>
        </div>

        <div class="detail-hero-main">
            <div class="detail-hero-info">
                <h1 class="detail-hero-title">{{ $production->name }}</h1>
                <div class="detail-hero-meta">
                    @if ($production->is_active)
                        <span class="status-badge status-active">
                            <span class="status-dot"></span>
                            Aktif
                        </span>
                    @else
                        <span class="status-badge status-inactive">
                            <span class="status-dot"></span>
                            Tidak Aktif
                        </span>
                    @endif
                    <span class="meta-chip">{{ $production->concept?->name ?? '-' }}</span>
                    <span class="meta-chip">{{ formatWeight($production->target_weight_kg) }} kg</span>
                    @if ($production->treatment_time)
                        <span class="meta-chip">{{ ucfirst($production->treatment_time) }}</span>
                    @endif
                </div>
                <p class="detail-hero-desc">Detail informasi pengobatan dan komposisi pakan.</p>
            </div>
            <div class="detail-hero-actions">
                <a class="btn btn-primary" href="{{ route('treatments.edit', $production) }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
                <a class="btn" href="{{ route('treatments.pdf', $production) }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Cetak PDF
                </a>
                <a class="btn btn-ghost" href="{{ route('treatments.index') }}">Kembali</a>
                <form method="POST" action="{{ route('treatments.destroy', $production) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus pengobatan?')">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 2: STATISTIC CARDS --}}
    {{-- ============================================ --}}
    @php
        $treatmentDays = $production->treatment_duration_days ?: 0;
        $currentDay = $production->treatment_day ?: 0;
        $pctUsed = $treatmentDays > 0 ? round(($currentDay / $treatmentDays) * 100) : 0;

        // Hitung total penambahan dari golongan + TAB
        $addedGroupKg = 0;
        foreach ($production->groups as $g) { foreach ($g->items as $gi) { $addedGroupKg += (float) $gi->weight_kg; } }
        $addedTabKg = 0;
        foreach ($production->tabs as $t) { foreach ($t->items as $ti) { $addedTabKg += (float) $ti->weight_kg; } }
        $addedKg = $addedGroupKg + $addedTabKg;
        $baseKg = (float) $production->target_weight_kg;
        $totalKg = $baseKg + $addedKg;
        $terpakaiKg = $addedKg;
        $sisaKg = $baseKg;
        $pctTerpakai = $totalKg > 0 ? round(($terpakaiKg / $totalKg) * 100) : 0;
        $rekomendasi = $sisaKg > 0 ? 'Kapasitas dasar ('.$baseKg.' kg) masih tersedia untuk alokasi golongan atau TAB baru.' : 'Kapasitas dasar sudah terpakai semua.';
    @endphp
    <div class="stats-row">
        <div class="stat-card-modern">
            <div class="stat-icon stat-icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Kapasitas</div>
                <div class="stat-value">{{ formatWeight($production->target_weight_kg) }} kg</div>
                <div class="stat-desc">Total kapasitas pakan</div>
            </div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-icon stat-icon-yellow">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Pengobatan Hari Ke</div>
                <div class="stat-value">{{ $currentDay }}</div>
                <div class="stat-desc">Dari total {{ $treatmentDays }} hari</div>
            </div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-icon stat-icon-green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Durasi Pengobatan</div>
                <div class="stat-value">{{ $treatmentDays ? $treatmentDays.' hari' : '-' }}</div>
                <div class="stat-desc">Total durasi</div>
            </div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-icon stat-icon-purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Mulai Pakai Konsep</div>
                <div class="stat-value">{{ $production->start_date?->format('d-m-Y') ?? '-' }}</div>
                <div class="stat-desc">Tanggal mulai</div>
            </div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-icon stat-icon-teal">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Penambahan</div>
                <div class="stat-value">{{ formatWeight($addedKg) }} kg</div>
                <div class="stat-desc">Dari golongan + TAB</div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 3: INFORMATION + SNAPSHOT GRID --}}
    {{-- ============================================ --}}
    <div class="detail-grid-2">
        {{-- LEFT: Information Card --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Informasi Pengobatan</span>
            </div>
            <div class="detail-card-body">
                <div class="info-rows">
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <div class="info-row-content">
                            <div class="info-row-label">Resep</div>
                            <div class="info-row-value">{{ $production->concept?->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="info-divider"></div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div class="info-row-content">
                            <div class="info-row-label">Lokasi</div>
                            <div class="info-row-value">{{ $production->location ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="info-divider"></div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
                        </div>
                        <div class="info-row-content">
                            <div class="info-row-label">Kandang</div>
                            <div class="info-row-value">{{ $production->cage ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="info-divider"></div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div class="info-row-content">
                            <div class="info-row-label">Tanggal Campur</div>
                            <div class="info-row-value">{{ $production->mix_date?->format('d-m-Y') ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="info-divider"></div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div class="info-row-content">
                            <div class="info-row-label">Mulai Pakai Konsep</div>
                            <div class="info-row-value">{{ $production->start_date?->format('d-m-Y') ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="info-divider"></div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div class="info-row-content">
                            <div class="info-row-label">Lama Pengobatan</div>
                            <div class="info-row-value">{{ $treatmentDays ? $treatmentDays.' hari' : '-' }}</div>
                        </div>
                    </div>
                    @if ($production->notes)
                    <div class="info-divider"></div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <div class="info-row-content">
                            <div class="info-row-label">Catatan</div>
                            <div class="info-row-value">{{ $production->notes }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: Snapshot Item --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                <span>Snapshot Item (Komposisi Pakan)</span>
            </div>
            <div class="detail-card-body p-0">
                <div class="table-modern-wrap">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width:44px;">No</th>
                                <th>Item</th>
                                <th style="width:90px;">Berat (kg)</th>
                                <th style="width:100px;">Sumber</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($production->items as $i => $row)
                                <tr>
                                    <td class="cell-number">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="item-name">{{ $row->item?->name }}</span>
                                    </td>
                                    <td class="cell-number">{{ formatWeight($row->weight_kg) }}</td>
                                    <td><span class="source-badge">{{ $row->source }}</span></td>
                                </tr>
                            @endforeach
                            @if ($production->items->isEmpty())
                                <tr><td colspan="4" class="empty-cell">Belum ada snapshot item.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 4: CAPACITY CARD --}}
    {{-- ============================================ --}}
    <div class="capacity-card">
        <div class="capacity-card-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            <span>Kapasitas Pakan</span>
        </div>
        <div class="capacity-card-body">
            <div class="capacity-layout">
                <div class="capacity-circle-wrap">
                    <div class="circular-progress" style="--pct: {{ $pctTerpakai }};">
                        <div class="circle-inner">
                            <span class="circle-pct">{{ $pctTerpakai }}%</span>
                            <span class="circle-label">Penambahan</span>
                        </div>
                    </div>
                </div>
                <div class="capacity-stats">
                    <div class="cap-stat-row">
                        <span class="cap-stat-label">Kapasitas Dasar (Konsep)</span>
                        <span class="cap-stat-value cap-used">{{ formatWeight($baseKg) }} kg</span>
                    </div>
                    <div class="cap-stat-row">
                        <span class="cap-stat-label">Penambahan (Golongan + TAB)</span>
                        <span class="cap-stat-value cap-remaining">{{ formatWeight($addedKg) }} kg</span>
                    </div>
                    <div class="cap-stat-row cap-total">
                        <span class="cap-stat-label">Total Keseluruhan</span>
                        <span class="cap-stat-value">{{ formatWeight($totalKg) }} kg</span>
                    </div>
                    <div class="cap-progress-bar-wrap">
                        <div class="cap-progress-track">
                            <div class="cap-progress-fill" style="width:{{ $pctTerpakai }}%;"></div>
                        </div>
                        <div class="cap-progress-labels">
                            <span>0 kg</span>
                            <span>{{ formatWeight($totalKg) }} kg</span>
                        </div>
                    </div>
                </div>
                <div class="capacity-recommend">
                    <div class="recommend-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    </div>
                    <div class="recommend-content">
                        <div class="recommend-title">Rekomendasi</div>
                        <div class="recommend-text">{{ $rekomendasi }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 5: MODE TOGGLE (Golongan / Tab) --}}
    {{-- ============================================ --}}
    <div class="mode-toggle-card">
        <div class="mode-toggle-inner">
            <div class="mode-toggle-group">
                <label class="mode-radio">
                    <input type="radio" name="input-mode" value="golongan" checked data-mode-toggle>
                    <span class="mode-radio-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Golongan
                    </span>
                </label>
                <label class="mode-radio">
                    <input type="radio" name="input-mode" value="tab" data-mode-toggle>
                    <span class="mode-radio-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Tab (Split Batch)
                    </span>
                </label>
            </div>
            <span class="mode-badge">Sisa: {{ formatWeight($tabAvailableKg) }} kg</span>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 6: GOLONGAN --}}
    {{-- ============================================ --}}
    <div id="mode-golongan" class="content-section">
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Golongan</span>
                <form method="POST" action="{{ route('treatments.groups.store', $production) }}" class="inline-form" style="margin-left:auto;">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Golongan" class="inline-input">
                    <button class="btn btn-primary btn-sm" type="submit">Tambah</button>
                </form>
            </div>
            <div class="detail-card-body">
                @forelse ($production->groups as $group)
                    <div class="group-card">
                        <div class="group-card-header">
                            <span class="group-name">{{ $group->name }}</span>
                            <div class="group-actions">
                                <form method="POST" action="{{ route('treatments.groups.destroy', $group) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('treatments.groups.items.store', $group) }}" class="group-item-form">
                            @csrf
                            <select name="item_id" class="item-select">
                                <option value="">Pilih Item</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" step="0.0001" name="weight_value" class="weight-input" placeholder="Berat">
                            <select name="weight_unit_id" class="unit-select">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            <label class="dosis-check">
                                <input type="checkbox" name="is_dosis" value="1" class="dosis-toggle" data-group-id="{{ $group->id }}">
                                <span>Dosis</span>
                            </label>
                            <button class="btn btn-primary btn-sm" type="submit">Tambah</button>
                        </form>
                        @if ($group->items->isNotEmpty())
                            <div class="table-modern-wrap">
                                <table class="table-modern">
                                    <thead>
                                        <tr>
                                            <th style="width:44px;">No</th>
                                            <th>Item</th>
                                            <th style="width:90px;">Berat</th>
                                            <th style="width:70px;">Dosis</th>
                                            <th style="width:150px;" class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($group->items as $gi)
                                            @php $dw = $gi->weight_input_value && $gi->inputUnit ? formatWeight($gi->weight_input_value).' '.$gi->inputUnit->name : formatWeight($gi->weight_kg).' kg'; @endphp
                                            <tr>
                                                <td class="cell-number">{{ $loop->iteration }}</td>
                                                <td><span class="item-name">{{ $gi->item?->name }}</span></td>
                                                <td class="weight-cell">
                                                    <span class="weight-display">{{ $dw }}</span>
                                                    <form method="POST" action="{{ route('treatments.groups.items.update', $gi) }}" class="edit-weight-form" style="display:none;gap:4px;align-items:center;">
                                                        @csrf @method('PUT')
                                                        <input type="number" step="0.0001" name="weight_value" value="{{ $gi->weight_input_value ?? formatWeight($gi->weight_kg) }}" style="width:70px;">
                                                        <select name="weight_unit_id" style="width:65px;">
                                                            @foreach ($units as $unit)
                                                                <option value="{{ $unit->id }}" @selected($gi->weight_input_unit_id == $unit->id)>{{ $unit->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button class="btn btn-sm" type="submit">Simpan</button>
                                                        <button type="button" class="btn btn-sm cancel-edit">Batal</button>
                                                    </form>
                                                </td>
                                                <td>@if($gi->is_dosis) <span class="dosis-badge-indigo">Dosis</span> @else <span class="text-muted">-</span> @endif</td>
                                                <td class="text-right">
                                                    <button type="button" class="btn btn-sm btn-edit-weight">Edit</button>
                                                    <form method="POST" action="{{ route('treatments.groups.items.destroy', $gi) }}" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-mini">Belum ada item dalam golongan ini.</div>
                        @endif
                    </div>
                @empty
                    <div class="empty-mini">Belum ada golongan. Buat golongan baru untuk menambahkan item.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 7: TAB --}}
    {{-- ============================================ --}}
    <div id="mode-tab" class="content-section" style="display:none;">
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Tab (Split Batch)</span>
                <form method="POST" action="{{ route('treatments.tabs.store', $production) }}" class="inline-form" style="margin-left:auto;">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Tab" class="inline-input" style="width:130px;">
                    <input type="number" step="0.0001" name="input_weight_value" placeholder="Ambil (kg)" class="inline-input" style="width:100px;">
                    <select name="input_weight_unit_id" class="inline-input" style="width:70px;">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary btn-sm" type="submit">Buat Tab</button>
                </form>
            </div>
            <div class="detail-card-body">
                @if ($production->tabs->isNotEmpty())
                    @php $cumulativeUsed = 0; @endphp
                    <div class="split-batch-summary">
                        <strong>Split Batch:</strong>
                        @foreach ($production->tabs as $tab)
                            @php $cumulativeUsed += (float) $tab->input_weight_kg; @endphp
                            <span class="chip-badge">{{ $tab->name }}: {{ formatWeight($tab->input_weight_kg) }} kg</span>
                            <span class="chip-badge">Sisa: {{ formatWeight($tab->remaining_weight_kg) }} kg</span>
                        @endforeach
                        <span class="chip-badge chip-primary">Total: {{ formatWeight($cumulativeUsed) }} kg</span>
                        <span class="chip-badge chip-primary">Sisa Global: {{ formatWeight($tabAvailableKg) }} kg</span>
                    </div>
                @endif

                @forelse ($production->tabs as $tab)
                    <div class="group-card">
                        <div class="group-card-header">
                            <div class="group-name-wrap">
                                <span class="group-name">{{ $tab->name }}</span>
                                <span class="chip-badge">Ambil: {{ formatWeight($tab->input_weight_kg) }} kg</span>
                                <span class="chip-badge">Sisa: {{ formatWeight($tab->remaining_weight_kg) }} kg</span>
                            </div>
                            <form method="POST" action="{{ route('treatments.tabs.destroy', $tab) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                        <form method="POST" action="{{ route('treatments.tabs.items.store', $tab) }}" class="group-item-form">
                            @csrf
                            <select name="item_id" class="item-select">
                                <option value="">Pilih Item</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" step="0.0001" name="weight_value" class="weight-input" placeholder="Berat">
                            <select name="weight_unit_id" class="unit-select">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            <label class="dosis-check">
                                <input type="checkbox" name="is_dosis" value="1" class="dosis-toggle" data-tab-id="{{ $tab->id }}" data-remaining-kg="{{ $tab->remaining_weight_kg }}">
                                <span>Dosis</span>
                            </label>
                            <button class="btn btn-primary btn-sm" type="submit">Tambah</button>
                        </form>
                        @if ($tab->items->isNotEmpty())
                            <div class="table-modern-wrap">
                                <table class="table-modern">
                                    <thead>
                                        <tr>
                                            <th style="width:44px;">No</th>
                                            <th>Item</th>
                                            <th style="width:90px;">Berat</th>
                                            <th style="width:70px;">Dosis</th>
                                            <th style="width:150px;" class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tab->items as $ti)
                                            @php $dw = $ti->weight_input_value && $ti->inputUnit ? formatWeight($ti->weight_input_value).' '.$ti->inputUnit->name : formatWeight($ti->weight_kg).' kg'; @endphp
                                            <tr>
                                                <td class="cell-number">{{ $loop->iteration }}</td>
                                                <td><span class="item-name">{{ $ti->item?->name }}</span></td>
                                                <td class="weight-cell">
                                                    <span class="weight-display">{{ $dw }}</span>
                                                    <form method="POST" action="{{ route('treatments.tabs.items.update', $ti) }}" class="edit-weight-form" style="display:none;gap:4px;align-items:center;">
                                                        @csrf @method('PUT')
                                                        <input type="number" step="0.0001" name="weight_value" value="{{ $ti->weight_input_value ?? formatWeight($ti->weight_kg) }}" style="width:70px;">
                                                        <select name="weight_unit_id" style="width:65px;">
                                                            @foreach ($units as $unit)
                                                                <option value="{{ $unit->id }}" @selected($ti->weight_input_unit_id == $unit->id)>{{ $unit->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button class="btn btn-sm" type="submit">Simpan</button>
                                                        <button type="button" class="btn btn-sm cancel-edit">Batal</button>
                                                    </form>
                                                </td>
                                                <td>@if($ti->is_dosis) <span class="dosis-badge-indigo">Dosis</span> @else <span class="text-muted">-</span> @endif</td>
                                                <td class="text-right">
                                                    <button type="button" class="btn btn-sm btn-edit-weight">Edit</button>
                                                    <form method="POST" action="{{ route('treatments.tabs.items.destroy', $ti) }}" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-mini">Belum ada item.</div>
                        @endif
                    </div>
                @empty
                    <div class="empty-mini">Belum ada TAB.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- DOSIS MODAL --}}
    {{-- ============================================ --}}
    <div class="modal-overlay" id="dosis-modal" style="display:none;">
        <div class="modal">
            <div class="modal-header">
                <h3>Kalkulator Dosis</h3>
                <button class="btn btn-ghost btn-sm" id="dosis-close" style="width:34px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-grid" style="gap:16px;">
                    <div class="field"><div class="label">Item</div><input type="text" id="dosis-item-name" readonly></div>
                    <div class="field"><div class="label">Kapasitas</div><input type="text" id="dosis-target" readonly value="{{ formatWeight($production->target_weight_kg) }} kg"></div>
                    <div class="field"><div class="label">Berat Dosis</div><input type="number" step="0.0001" id="dosis-weight" placeholder="1"></div>
                    <div class="field"><div class="label">Satuan</div><select id="dosis-unit">@foreach ($units as $unit)<option value="{{ $unit->conversion_to_kg }}">{{ $unit->name }}</option>@endforeach</select></div>
                    <div class="field"><div class="label">Per</div><input type="number" step="0.0001" id="dosis-per" placeholder="1" value="1"></div>
                    <div class="field"><div class="label">Satuan Per</div><select id="dosis-per-unit">@foreach ($units as $unit)<option value="{{ $unit->conversion_to_kg }}">{{ $unit->name }}</option>@endforeach</select></div>
                </div>
                <div class="divider"></div>
                <div class="dosis-result-box">
                    <div class="dosis-result-label">Hasil Perhitungan:</div>
                    <strong class="dosis-result-value" id="dosis-result">0 kg</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" id="dosis-close-btn">Batal</button>
                <button class="btn btn-primary" id="dosis-pakai">Pakai Hasil</button>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- JAVASCRIPT (unchanged logic) --}}
    {{-- ============================================ --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        function switchMode(mode) {
            document.getElementById('mode-golongan').style.display = mode === 'golongan' ? '' : 'none';
            document.getElementById('mode-tab').style.display = mode === 'tab' ? '' : 'none';
        }
        let currentMode = document.querySelector('[data-mode-toggle]:checked')?.value || 'golongan';
        if (window.location.hash === '#tab') {
            currentMode = 'tab';
            document.querySelectorAll('[data-mode-toggle]').forEach(r => r.checked = r.value === 'tab');
            switchMode('tab');
        }
        function panelHasUnsavedInputs(mode) {
            const panel = document.getElementById(mode === 'golongan' ? 'mode-golongan' : 'mode-tab');
            if (!panel) return false;
            for (const el of panel.querySelectorAll('input[type="number"], input[type="text"], textarea')) { if (el.value.trim()) return true; }
            for (const el of panel.querySelectorAll('select')) { if (el.selectedIndex > 0) return true; }
            return false;
        }
        document.querySelectorAll('[data-mode-toggle]').forEach(radio => {
            radio.addEventListener('click', function(e) {
                const newMode = this.value;
                if (newMode === currentMode) return;
                if (panelHasUnsavedInputs(currentMode) && !confirm('Mengganti mode akan menghapus input yang belum disimpan. Lanjutkan?')) {
                    e.preventDefault();
                    document.querySelectorAll('[data-mode-toggle]').forEach(r => r.checked = r.value === currentMode);
                    return;
                }
                switchMode(newMode);
                currentMode = newMode;
            });
        });
        const productionTargetKg = {{ $production->target_weight_kg }};
        const dosisModal = document.getElementById('dosis-modal');
        const dosisItemName = document.getElementById('dosis-item-name');
        const dosisWeight = document.getElementById('dosis-weight');
        const dosisUnit = document.getElementById('dosis-unit');
        const dosisPer = document.getElementById('dosis-per');
        const dosisPerUnit = document.getElementById('dosis-per-unit');
        const dosisResult = document.getElementById('dosis-result');
        const dosisTarget = document.getElementById('dosis-target');
        let activeForm = null, pendingDosisToggle = null, isTabContext = false, dosisTargetKg = productionTargetKg;
        function recalcDosis() {
            const w = parseFloat(dosisWeight.value) || 0, c = parseFloat(dosisUnit.value) || 1, p = parseFloat(dosisPer.value) || 1, pc = parseFloat(dosisPerUnit.value) || 1;
            if (w <= 0) { dosisResult.textContent = '0 kg'; return; }
            dosisResult.textContent = (((w * c) / (p * pc)) * dosisTargetKg).toFixed(4) + ' kg';
        }
        function openDosisModal(form, toggleCb) {
            activeForm = form; pendingDosisToggle = toggleCb;
            const itemSelect = form.querySelector('.item-select');
            dosisItemName.value = itemSelect.options[itemSelect.selectedIndex]?.text || '';
            const tabId = toggleCb?.dataset.tabId;
            isTabContext = !!tabId;
            if (isTabContext) {
                dosisTargetKg = parseFloat(toggleCb.dataset.remainingKg) || 0;
                dosisTarget.value = dosisTargetKg.toFixed(2) + ' kg (sisa tab)';
            } else {
                dosisTargetKg = productionTargetKg;
                dosisTarget.value = '{{ formatWeight($production->target_weight_kg) }} kg';
            }
            form.querySelector('.weight-input').disabled = true;
            form.querySelector('.weight-input').placeholder = 'Dosis (auto)';
            form.querySelector('select[name="weight_unit_id"]').disabled = true;
            dosisModal.style.display = 'flex';
            recalcDosis();
        }
        function closeDosisModal() {
            dosisModal.style.display = 'none';
            if (activeForm) {
                activeForm.querySelector('.weight-input').disabled = false;
                activeForm.querySelector('.weight-input').placeholder = 'Berat';
                activeForm.querySelector('select[name="weight_unit_id"]').disabled = false;
                if (pendingDosisToggle) pendingDosisToggle.checked = false;
            }
            activeForm = null; pendingDosisToggle = null;
        }
        dosisWeight.addEventListener('input', recalcDosis);
        dosisUnit.addEventListener('change', recalcDosis);
        dosisPer.addEventListener('input', recalcDosis);
        dosisPerUnit.addEventListener('change', recalcDosis);
        document.getElementById('dosis-pakai').addEventListener('click', function() {
            const w = parseFloat(dosisWeight.value) || 0, c = parseFloat(dosisUnit.value) || 1, p = parseFloat(dosisPer.value) || 1, pc = parseFloat(dosisPerUnit.value) || 1;
            if (w <= 0) return;
            const result = (((w * c) / (p * pc)) * dosisTargetKg);
            const weightInput = activeForm.querySelector('.weight-input');
            weightInput.value = result.toFixed(4);
            weightInput.disabled = false;
            const unitSelect = activeForm.querySelector('select[name="weight_unit_id"]');
            const kgOpt = Array.from(unitSelect.options).find(o => parseFloat(o.value) === 1);
            if (kgOpt) kgOpt.selected = true;
            unitSelect.disabled = false;
            dosisModal.style.display = 'none';
            activeForm.submit();
        });
        document.getElementById('dosis-close').addEventListener('click', closeDosisModal);
        document.getElementById('dosis-close-btn').addEventListener('click', closeDosisModal);
        document.querySelectorAll('.dosis-toggle').forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) {
                    const form = this.closest('form');
                    if (!form.querySelector('.item-select').value) { alert('Pilih item terlebih dahulu.'); this.checked = false; return; }
                    openDosisModal(form, this);
                } else {
                    const form = this.closest('form');
                    form.querySelector('.weight-input').disabled = false;
                    form.querySelector('select[name="weight_unit_id"]').disabled = false;
                }
            });
        });
        document.querySelectorAll('.btn-edit-weight').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                row.querySelector('.weight-display').style.display = 'none';
                row.querySelector('.edit-weight-form').style.display = 'inline-flex';
                this.style.display = 'none';
            });
        });
        document.querySelectorAll('.cancel-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                row.querySelector('.weight-display').style.display = '';
                row.querySelector('.edit-weight-form').style.display = 'none';
                row.querySelector('.btn-edit-weight').style.display = '';
            });
        });
    });
    </script>

    {{-- ============================================ --}}
    {{-- INLINE STYLES FOR NEW COMPONENTS --}}
    {{-- ============================================ --}}
    <style>
    /* Detail Hero */
    .detail-hero { margin-bottom:24px; }
    .detail-hero-breadcrumb { display:flex; align-items:center; gap:6px; font-size:12px; color:var(--text-muted); margin-bottom:12px; }
    .detail-hero-breadcrumb a { color:var(--primary); }
    .detail-hero-breadcrumb svg { width:12px; height:12px; flex-shrink:0; }
    .detail-hero-main { display:flex; align-items:flex-start; justify-content:space-between; gap:20px; flex-wrap:wrap; }
    .detail-hero-info { flex:1; min-width:0; }
    .detail-hero-title { font-size:28px; font-weight:700; color:var(--text); letter-spacing:-.5px; margin:0 0 8px; line-height:1.2; }
    .detail-hero-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:8px; }
    .detail-hero-desc { font-size:13px; color:var(--text-secondary); margin:0; }
    .detail-hero-actions { display:flex; gap:8px; flex-wrap:wrap; flex-shrink:0; }

    /* Status Badge */
    .status-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; }
    .status-active { background:rgba(22,163,74,.1); color:#166534; border:1px solid rgba(22,163,74,.2); }
    .status-inactive { background:rgba(220,38,38,.1); color:#991B1B; border:1px solid rgba(220,38,38,.2); }
    .status-dot { width:7px; height:7px; border-radius:50%; }
    .status-active .status-dot { background:#16A34A; }
    .status-inactive .status-dot { background:#DC2626; }
    .meta-chip { display:inline-flex; padding:3px 10px; border-radius:6px; background:var(--card-alt); color:var(--text-secondary); font-size:12px; font-weight:500; border:1px solid var(--border); }

    /* Stat Cards */
    .stats-row { display:grid; grid-template-columns:repeat(5,1fr); gap:16px; margin-bottom:24px; }
    .stat-card-modern { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,.04); }
    .stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:12px; }
    .stat-icon-blue { background:rgba(37,99,235,.1); color:var(--primary); }
    .stat-icon-yellow { background:rgba(245,158,11,.1); color:#B45309; }
    .stat-icon-green { background:rgba(22,163,74,.1); color:var(--success); }
    .stat-icon-purple { background:rgba(139,92,246,.1); color:#7C3AED; }
    .stat-icon-teal { background:rgba(8,145,178,.1); color:var(--info); }
    .stat-content .stat-label { font-size:11px; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:.04em; margin-bottom:2px; }
    .stat-content .stat-value { font-size:22px; font-weight:700; color:var(--text); letter-spacing:-.3px; line-height:1.2; }
    .stat-content .stat-desc { font-size:11px; color:var(--text-muted); margin-top:2px; }

    /* 2-Column Grid */
    .detail-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px; }
    .detail-card { background:var(--card); border:1px solid var(--border); border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,.04); overflow:hidden; }
    .detail-card-header { display:flex; align-items:center; gap:8px; padding:18px 20px; border-bottom:1px solid var(--border); font-size:14px; font-weight:600; color:var(--text); }
    .detail-card-header svg { color:var(--text-muted); flex-shrink:0; }
    .detail-card-body { padding:20px; }
    .detail-card-body.p-0 { padding:0; }

    /* Info Rows */
    .info-rows { display:grid; gap:0; }
    .info-row { display:flex; align-items:flex-start; gap:12px; padding:8px 0; }
    .info-row-icon { width:28px; height:28px; border-radius:8px; background:var(--card-alt); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:var(--text-muted); }
    .info-row-content { flex:1; min-width:0; }
    .info-row-label { font-size:11px; color:var(--text-muted); margin-bottom:1px; }
    .info-row-value { font-size:14px; font-weight:600; color:var(--text); }
    .info-divider { height:1px; background:var(--border-light); }

    /* Modern Table */
    .table-modern-wrap { overflow-x:auto; }
    .table-modern { width:100%; border-collapse:collapse; font-size:13px; }
    .table-modern thead th { background:var(--card-alt); padding:10px 14px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase; letter-spacing:.04em; color:var(--text-secondary); border-bottom:1px solid var(--border); white-space:nowrap; }
    .table-modern tbody tr { transition:background .08s ease; }
    .table-modern tbody tr:nth-child(even) { background:rgba(248,250,252,.4); }
    .table-modern tbody tr:hover { background:var(--card-alt); }
    .table-modern tbody td { padding:10px 14px; border-bottom:1px solid var(--border-light); vertical-align:middle; }
    .table-modern tbody tr:last-child td { border-bottom:none; }
    .cell-number { font-weight:600; color:var(--text-secondary); }
    .item-name { font-weight:500; color:var(--text); }
    .source-badge { display:inline-block; padding:3px 8px; border-radius:999px; background:#EEF2FF; color:#4F46E5; font-size:11px; font-weight:600; }
    .text-right { text-align:right; }
    .text-muted { color:var(--text-muted); }
    .empty-cell { text-align:center; padding:32px !important; color:var(--text-muted); }

    /* Capacity Card */
    .capacity-card { background:var(--card); border:1px solid var(--border); border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,.04); margin-bottom:24px; }
    .capacity-card-header { display:flex; align-items:center; gap:8px; padding:18px 20px; border-bottom:1px solid var(--border); font-size:14px; font-weight:600; }
    .capacity-card-header svg { color:var(--text-muted); }
    .capacity-card-body { padding:24px; }
    .capacity-layout { display:grid; grid-template-columns:auto 1fr auto; gap:32px; align-items:center; }
    .capacity-circle-wrap { display:flex; align-items:center; justify-content:center; }
    .circular-progress { width:120px; height:120px; border-radius:50%; background:conic-gradient(var(--primary) calc(var(--pct)*3.6deg), #E2E8F0 calc(var(--pct)*3.6deg)); display:flex; align-items:center; justify-content:center; position:relative; }
    .circular-progress::before { content:''; position:absolute; width:90px; height:90px; border-radius:50%; background:var(--card); }
    .circle-inner { position:relative; z-index:1; text-align:center; }
    .circle-pct { display:block; font-size:22px; font-weight:700; color:var(--primary); line-height:1; }
    .circle-label { display:block; font-size:10px; color:var(--text-muted); margin-top:2px; }
    .capacity-stats { display:grid; gap:10px; }
    .cap-stat-row { display:flex; justify-content:space-between; align-items:center; }
    .cap-stat-label { font-size:13px; color:var(--text-secondary); }
    .cap-stat-value { font-size:15px; font-weight:600; }
    .cap-used { color:var(--primary); }
    .cap-remaining { color:var(--success); }
    .cap-total { padding-top:8px; border-top:1px solid var(--border); }
    .cap-progress-bar-wrap { margin-top:8px; }
    .cap-progress-track { height:10px; background:var(--card-alt); border-radius:999px; overflow:hidden; }
    .cap-progress-fill { height:100%; border-radius:999px; background:linear-gradient(90deg,var(--primary),#60A5FA); transition:width .4s ease; }
    .cap-progress-labels { display:flex; justify-content:space-between; font-size:10px; color:var(--text-muted); margin-top:3px; }
    .capacity-recommend { display:flex; gap:12px; padding:16px; background:var(--card-alt); border:1px solid var(--border); border-radius:16px; max-width:220px; }
    .recommend-icon { width:36px; height:36px; border-radius:50%; background:rgba(37,99,235,.1); color:var(--primary); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .recommend-content .recommend-title { font-size:12px; font-weight:600; color:var(--text); margin-bottom:4px; }
    .recommend-content .recommend-text { font-size:11px; color:var(--text-secondary); line-height:1.4; }

    /* Mode Toggle */
    .mode-toggle-card { background:var(--card); border:1px solid var(--border); border-radius:16px; margin-bottom:24px; }
    .mode-toggle-inner { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:14px 20px; flex-wrap:wrap; }
    .mode-toggle-group { display:flex; gap:8px; }
    .mode-radio { cursor:pointer; }
    .mode-radio input { display:none; }
    .mode-radio-label { display:flex; align-items:center; gap:6px; padding:8px 14px; border-radius:10px; border:1px solid var(--border); font-size:13px; font-weight:500; color:var(--text-secondary); transition:all .12s ease; cursor:pointer; }
    .mode-radio input:checked + .mode-radio-label { background:var(--primary); border-color:var(--primary); color:#fff; }
    .mode-radio-label svg { width:16px; height:16px; }
    .mode-badge { padding:4px 12px; border-radius:999px; background:var(--card-alt); color:var(--text-secondary); font-size:11px; font-weight:600; border:1px solid var(--border); }

    /* Group Card */
    .group-card { background:var(--card-alt); border:1px solid var(--border); border-radius:12px; padding:16px; margin-bottom:12px; }
    .group-card-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; flex-wrap:wrap; gap:8px; }
    .group-name { font-size:15px; font-weight:700; color:var(--text); }
    .group-name-wrap { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .group-actions { display:flex; gap:6px; }
    .group-item-form { display:flex; gap:8px; align-items:end; flex-wrap:wrap; margin-bottom:10px; }
    .group-item-form .item-select { flex:1; min-width:140px; }
    .group-item-form .weight-input { width:90px; }
    .group-item-form .unit-select { width:80px; }
    .dosis-check { display:flex; align-items:center; gap:4px; cursor:pointer; font-size:13px; white-space:nowrap; padding:4px 0; }
    .dosis-check input { width:16px; height:16px; }
    .inline-form { display:flex; gap:8px; align-items:end; }
    .inline-input { height:36px; padding:0 10px; border-radius:8px; border:1px solid var(--border); font-size:12px; background:var(--card); color:var(--text); outline:none; }
    .inline-input:focus { border-color:var(--primary); }

    /* Split Batch Summary */
    .split-batch-summary { display:flex; flex-wrap:wrap; gap:8px; padding:12px 16px; background:var(--primary-light); border:1px solid var(--primary-border); border-radius:12px; margin-bottom:16px; font-size:13px; align-items:center; }
    .chip-badge { display:inline-block; padding:3px 10px; border-radius:999px; background:var(--card); color:var(--text-secondary); font-size:11px; font-weight:600; border:1px solid var(--border); }
    .chip-primary { background:var(--primary-light); color:var(--primary); border-color:var(--primary-border); }

    /* Dosis Badge */
    .dosis-badge-indigo { display:inline-block; padding:2px 8px; border-radius:999px; background:#EEF2FF; color:#4F46E5; font-size:11px; font-weight:600; }

    /* Dosis Result */
    .dosis-result-box { background:rgba(22,163,74,.06); border-radius:12px; text-align:center; padding:16px; }
    .dosis-result-label { font-size:12px; color:var(--text-secondary); margin-bottom:4px; }
    .dosis-result-value { font-size:24px; color:var(--success); }

    /* Empty Mini */
    .empty-mini { text-align:center; padding:16px; color:var(--text-muted); font-size:13px; }

    /* Responsive */
    @media (max-width:1200px) {
        .stats-row { grid-template-columns:repeat(3,1fr); }
        .capacity-layout { grid-template-columns:1fr 1fr; }
        .capacity-recommend { max-width:none; }
    }
    @media (max-width:900px) {
        .detail-grid-2 { grid-template-columns:1fr; }
        .stats-row { grid-template-columns:repeat(2,1fr); }
    }
    @media (max-width:640px) {
        .stats-row { grid-template-columns:1fr; }
        .capacity-layout { grid-template-columns:1fr; }
        .detail-hero-main { flex-direction:column; }
        .detail-hero-actions { width:100%; }
        .detail-hero-actions .btn { flex:1; justify-content:center; }
        .mode-toggle-inner { flex-direction:column; align-items:stretch; }
        .mode-toggle-group { justify-content:center; }
        .detail-card-header { flex-wrap:wrap; }
        .inline-form { flex-wrap:wrap; }
        .inline-form .inline-input { flex:1; min-width:0; }
        .detail-hero-title { font-size:22px; }
    }
    </style>
</x-layouts.dashboard>
