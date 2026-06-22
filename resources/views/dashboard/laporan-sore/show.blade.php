<x-layouts.dashboard :title="'Laporan: '.$laporanSore->tanggal->format('d-m-Y')" :heading="'Laporan '.$laporanSore->tanggal->format('d-m-Y')">
    <div class="page-hero">
        <h1>Laporan Sore — {{ $laporanSore->tanggal->format('d-m-Y') }}</h1>
        <p>{{ $laporanSore->location?->name }} • Dibuat oleh {{ $laporanSore->user?->name }}</p>
        <div class="page-hero-actions">
            <a class="btn btn-ghost" href="{{ route('laporan-sore.index') }}">Kembali</a>
            <form method="POST" action="{{ route('laporan-sore.destroy', $laporanSore) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus laporan?')">Hapus</button>
            </form>
        </div>
    </div>

    @php
        $sections = [
            'sisa_kemarin' => ['label' => 'Sisa Kemarin', 'color' => '#F59E0B'],
            'campuran_hari_ini' => ['label' => 'Campuran Hari Ini', 'color' => '#3B82F6'],
            'kirim_hari_ini' => ['label' => 'Kirim Hari Ini', 'color' => '#10B981'],
            'stock' => ['label' => 'Stock', 'color' => '#8B5CF6'],
        ];
    @endphp

    @foreach ($sections as $key => $sec)
        @php
            $sectionDetails = $laporanSore->details->where('section', $key)->groupBy(fn ($d) => $d->cage_id . '|' . $d->nama_tali);
        @endphp
        @if ($sectionDetails->isNotEmpty())
        <div class="content-section">
            <div class="card">
                <div class="card-header" style="border-left: 4px solid {{ $sec['color'] }};">
                    <h3>{{ $sec['label'] }}</h3>
                    <span class="badge badge-muted">{{ $sectionDetails->count() }} kandang</span>
                </div>
                <div class="card-body-sm" style="padding: 0;">
                    <div class="table-wrap" style="border: none; border-radius: 0;">
                        <table class="data">
                            <thead>
                                <tr>
                                    <th>Kandang</th>
                                    <th>Nama Tali</th>
                                    <th>Konsep</th>
                                    <th>Item Tambahan</th>
                                    <th style="width: 80px;">Jumlah</th>
                                    <th style="width: 80px;">Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sectionDetails as $groupKey => $details)
                                    @foreach ($details as $i => $detail)
                                        <tr>
                                            @if ($i === 0)
                                                <td rowspan="{{ $details->count() }}"><strong>{{ $detail->cage?->name ?? '-' }}</strong></td>
                                                <td rowspan="{{ $details->count() }}">{{ $detail->nama_tali ?? '-' }}</td>
                                            @endif
                                            <td>{{ $detail->konsep?->name }}</td>
                                            <td>
                                                @forelse ($detail->items as $pivot)
                                                    <span class="badge badge-muted" style="margin: 1px;">{{ $pivot->item?->name }}</span>
                                                @empty
                                                    <span class="cell-muted">-</span>
                                                @endforelse
                                            </td>
                                            <td>{{ number_format($detail->jumlah, 2) }}</td>
                                            <td class="cell-muted">{{ $detail->satuan }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    @php
        $hasData = collect($sections)->keys()->filter(fn ($k) => $laporanSore->details->where('section', $k)->isNotEmpty())->isNotEmpty();
    @endphp
    @if (!$hasData)
    <div class="content-section">
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <h3>Belum Ada Data</h3>
                <p>Laporan ini belum memiliki detail.</p>
            </div>
        </div>
    </div>
    @endif
</x-layouts.dashboard>
