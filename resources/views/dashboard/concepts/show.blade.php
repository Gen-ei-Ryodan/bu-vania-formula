<x-layouts.dashboard :title="'Konsep: '.$concept->name" :heading="$concept->name">
    <div class="page-hero">
        <h1>{{ $concept->name }}</h1>
        <p>Detail resep pakan</p>
        <div class="page-hero-actions">
            <a class="btn" href="{{ route('concepts.edit', $concept) }}">Edit</a>
            <a class="btn btn-ghost" href="{{ route('concepts.index') }}">Kembali</a>
            <form method="POST" action="{{ route('concepts.destroy', $concept) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus konsep?')">Hapus</button>
            </form>
        </div>
    </div>

    <div class="content-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(37,99,235,.1); color: var(--primary);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="label">Nama</div>
                <div class="value">{{ $concept->name }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(139,92,246,.1); color: #7C3AED;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="label">Konsep Dari</div>
                <div class="value">{{ $concept->pembuat?->name ?? '-' }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(22,163,74,.1); color: var(--success);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg>
                </div>
                <div class="label">Berat Dasar</div>
                <div class="value">{{ formatWeight($concept->base_weight_kg) }} kg</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(245,158,11,.1); color: #B45309;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="label">Jumlah Item</div>
                <div class="value">{{ $concept->items->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(6,182,212,.1); color: #0891B2;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="label">Tanggal Mulai</div>
                <div class="value">{{ $concept->start_date?->format('d M Y') ?? '-' }}</div>
            </div>
            @if ($concept->notes)
            <div class="stat-card" style="grid-column: span 2;">
                <div class="stat-card-icon" style="background: rgba(100,116,139,.1); color: var(--text-muted);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="label">Keterangan</div>
                <div class="value">{{ $concept->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h3>Komposisi Item</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="width: 120px;">Berat (kg)</th>
                                <th style="width: 100px;">Persen</th>
                                <th style="width: 150px;">Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($concept->items as $row)
                                <tr>
                                    <td><strong>{{ $row->item?->name }}</strong></td>
                                    <td>{{ formatWeight($row->weight_kg) }}</td>
                                    <td><span class="badge badge-muted">{{ number_format($row->percentage, 2) }}%</span></td>
                                    <td>{{ formatCurrency($row->price) }}</td>
                                </tr>
                            @endforeach
                            @if ($concept->items->isEmpty())
                                 <tr><td colspan="4" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada item.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="summary-box">
            <div class="summary-item">
                <div class="label">Total Harga Resep</div>
                <div class="value">{{ formatCurrency($concept->total_price) }}</div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
