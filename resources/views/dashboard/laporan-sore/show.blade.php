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
        $sectionConfigs = [
            'sisa_kemarin' => ['label' => 'Sisa Kemarin', 'color' => '#F59E0B', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'campuran_hari_ini' => ['label' => 'Campuran Hari Ini', 'color' => '#3B82F6', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            'kirim_hari_ini' => ['label' => 'Kirim Hari Ini', 'color' => '#10B981', 'icon' => 'M9 5l7 7-7 7'],
            'stock' => ['label' => 'Stock', 'color' => '#8B5CF6', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ];

        $groupedSections = [];
        foreach ($sectionConfigs as $key => $config) {
            $sectionDetails = $laporanSore->details->where('section', $key);
            $groupedSections[$key] = [
                'config' => $config,
                'groups' => $sectionDetails->isNotEmpty()
                    ? $sectionDetails->groupBy(fn ($d) => $d->cage_id . '|' . $d->nama_tali)
                    : collect(),
            ];
        }

        $tanggal = $laporanSore->tanggal;
        $h1 = $tanggal->copy()->subDay();
        $locName = $laporanSore->location?->name ?? '';
    @endphp

    {{-- ======== TEKS VIEW + SALIN ======== --}}
    <div class="content-section" style="max-width: 720px; margin: 0 auto;">
        <div class="card">
            <div class="card-header" style="justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <h3 style="margin: 0;">📋 Teks Laporan</h3>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button class="btn btn-success" id="btn-download-gambar" style="padding: 8px 20px; font-size: 14px; font-weight: 600;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download Gambar
                    </button>
                    <button class="btn btn-primary" id="btn-salin-teks" style="padding: 8px 20px; font-size: 14px; font-weight: 600;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Teks
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="teks-container" style="font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.7; white-space: pre-wrap; background: var(--card-alt); border: 1px solid var(--border); border-radius: 10px; padding: 20px; color: var(--text);">
                    {{-- Teks akan di-generate oleh JS --}}
                    <span class="cell-muted">Memuat teks...</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ======== DETAIL PER SECTION ======== --}}
    <div class="content-section" style="max-width: 960px; margin: 24px auto 0;">
        @foreach ($groupedSections as $key => $groupData)
            @php $sec = $groupData['config']; @endphp
            <div class="card" style="margin-bottom: 16px;">
                <div class="card-header" style="border-left: 4px solid {{ $sec['color'] }};">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $sec['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $sec['icon'] }}"/></svg>
                        <h3 style="margin: 0;">{{ $sec['label'] }}</h3>
                        <span class="badge badge-muted">{{ $groupData['groups']->count() }} kandang</span>
                    </div>
                </div>
                <div class="card-body" style="padding: 0;">
                    @forelse ($groupData['groups'] as $groupKey => $details)
                        @php
                            $first = $details->first();
                            $cageName = $first->cage?->name ?? '(tanpa kandang)';
                            $tali = $first->nama_tali;
                        @endphp
                        <div class="detail-cage-group" style="border-bottom: 1px solid var(--border-light); padding: 14px 16px;">
                            <div class="detail-cage-title" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
                                <strong style="font-size: 15px;">{{ $cageName }}</strong>
                                @if ($tali)
                                    <span class="detail-tali-badge">({{ $tali }})</span>
                                @endif
                            </div>
                            <div class="detail-konsep-list">
                                @foreach ($details as $detail)
                                    <div class="detail-konsep-item">
                                        <span class="detail-bullet">•</span>
                                        <span class="detail-konsep-name">{{ $detail->konsep?->name ?? '?' }}</span>
                                        @if ($detail->items->isNotEmpty())
                                            <span class="detail-plus">+</span>
                                            @foreach ($detail->items as $pivot)
                                                <span class="detail-item-tag">{{ $pivot->item?->name }}</span>
                                                @if (!$loop->last)
                                                    <span class="detail-plus">+</span>
                                                @endif
                                            @endforeach
                                        @endif
                                        <span class="detail-eq">=</span>
                                        <span class="detail-jumlah">{{ number_format($detail->jumlah, 0) }}</span>
                                        <span class="detail-satuan">{{ $detail->satuan }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div style="padding: 24px 16px; text-align: center; color: var(--text-muted); font-size: 14px;">-</div>
                    @endforelse
                </div>
            </div>
        @endforeach

        @if (empty($groupedSections))
            <div class="card">
                <div class="empty-state">
                    <h3>Belum Ada Data</h3>
                    <p>Laporan ini belum memiliki detail.</p>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('teks-container');
        const btnSalin = document.getElementById('btn-salin-teks');
        const btnDownload = document.getElementById('btn-download-gambar');

        function generateTeks() {
            const tanggal = '{{ $tanggal->format('d.m.Y') }}';
            const h1 = '{{ $h1->format('d.m.Y') }}';
            const loc = '{{ $locName }}';
            const lines = [];

            lines.push('*' + loc + '*' + ' ' + tanggal);
            lines.push('');

            const sections = @json($groupedSections);

            if (sections.sisa_kemarin) {
                lines.push('*SISA - ' + h1 + '*');
                renderSection(sections.sisa_kemarin, lines);
                lines.push('');
            } else {
                lines.push('*SISA - ' + h1 + '*');
                lines.push('-');
                lines.push('');
            }

            if (sections.campuran_hari_ini) {
                lines.push('*CAMPURAN :*');
                renderSection(sections.campuran_hari_ini, lines);
                lines.push('');
            } else {
                lines.push('*CAMPURAN :*');
                lines.push('-');
                lines.push('');
            }

            if (sections.kirim_hari_ini) {
                lines.push('*KIRIM :*');
                renderSection(sections.kirim_hari_ini, lines);
                lines.push('');
            } else {
                lines.push('*KIRIM :*');
                lines.push('-');
                lines.push('');
            }

            if (sections.stock) {
                lines.push('*STOCK :*');
                renderSection(sections.stock, lines);
                lines.push('');
            } else {
                lines.push('*STOCK :*');
                lines.push('-');
                lines.push('');
            }

            return lines.join('\n');
        }

        function renderSection(sectionData, lines) {
            const groups = sectionData.groups;
            const keys = Object.keys(groups);
            if (keys.length === 0) {
                lines.push('-');
                return;
            }
            keys.forEach(function (groupKey) {
                const details = groups[groupKey];
                const first = details[0];
                var cageName = first.cage ? first.cage.name : '(tanpa kandang)';
                var tali = first.nama_tali || '';

                var cageLine = '*' + cageName;
                if (tali) {
                    cageLine += ' (' + tali + ')';
                }
                cageLine += '*';
                lines.push(cageLine);

                details.forEach(function (detail) {
                    var parts = [];
                    parts.push('• ' + (detail.konsep ? detail.konsep.name : '?'));

                    if (detail.items && detail.items.length > 0) {
                        detail.items.forEach(function (pivot) {
                            parts.push('+');
                            parts.push(pivot.item ? pivot.item.name : '?');
                        });
                    }

                    parts.push('=');
                    parts.push(formatAngka(detail.jumlah));
                    parts.push(detail.satuan);

                    lines.push(parts.join(' '));
                });
            });
        }

        function formatAngka(val) {
            var num = parseFloat(val);
            if (isNaN(num)) return '0';
            return num % 1 === 0 ? num.toFixed(0) : num.toFixed(2);
        }

        function renderTeks() {
            var teks = generateTeks();
            container.textContent = teks;
        }

        renderTeks();

        btnSalin.addEventListener('click', function () {
            var teks = generateTeks();

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(teks).then(function () {
                    showSalinSuccess();
                }).catch(function () {
                    fallbackCopy(teks);
                });
            } else {
                fallbackCopy(teks);
            }
        });

        function showSalinSuccess() {
            btnSalin.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><polyline points="20 6 9 17 4 12"/></svg> Tersalin!';
            btnSalin.className = 'btn btn-success';
            setTimeout(function () {
                btnSalin.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Salin Teks';
                btnSalin.className = 'btn btn-primary';
            }, 2000);
        }

        function fallbackCopy(text) {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            try {
                document.execCommand('copy');
                showSalinSuccess();
            } catch (e) {}
            document.body.removeChild(ta);
        }

        btnDownload.addEventListener('click', function () {
            btnDownload.disabled = true;
            btnDownload.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; animation: spin 1s linear infinite;"><path d="M12 2v4m0 12v4m-7.07-3.93l2.83-2.83m8.48-8.48l2.83-2.83M2 12h4m12 0h4m-3.93 7.07l-2.83-2.83M6.34 6.34L3.51 3.51"/></svg> Membuat gambar...';

            requestAnimationFrame(function () {
                try {
                    generateAndDownloadImage();
                } catch (e) {
                    console.error(e);
                    alert('Gagal membuat gambar. Silakan coba lagi.');
                }
                btnDownload.disabled = false;
                btnDownload.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Download Gambar';
            });
        });

        function generateAndDownloadImage() {
            var teks = generateTeks();
            var lines = teks.split('\n');

            var dpr = window.devicePixelRatio || 1;
            var paddingX = 40;
            var paddingY = 36;
            var lineHeight = 30;
            var fontSize = 20;
            var headerFontSize = 26;
            var sectionFontSize = 22;
            var cageFontSize = 20;
            var fontFamily = "'Segoe UI', 'Helvetica Neue', Arial, sans-serif";

            var bgColor = '#FFFFFF';
            var textColor = '#1a1a1a';
            var boldColor = '#000000';
            var headerColor = '#0D6E3D';
            var sectionColor = '#0D6E3D';
            var cageColor = '#1558B0';
            var footerColor = '#888888';

            var measureCtx = document.createElement('canvas').getContext('2d');

            var parsedLines = [];
            var maxLineWidth = 0;

            lines.forEach(function (line, idx) {
                var parsed = parseLine(line);
                parsedLines.push(parsed);

                var w = 0;
                if (idx === 0) {
                    measureCtx.font = 'bold ' + headerFontSize + 'px ' + fontFamily;
                    w += measureCtx.measureText(parsed.headerText || line).width;
                    if (parsed.headerDate) {
                        measureCtx.font = (fontSize - 2) + 'px ' + fontFamily;
                        w += measureCtx.measureText(' ' + parsed.headerDate).width;
                    }
                } else if (line === '') {
                    w = 0;
                } else if (parsed.isHeader) {
                    measureCtx.font = 'bold ' + headerFontSize + 'px ' + fontFamily;
                    w = measureCtx.measureText(parsed.text).width;
                } else if (parsed.isSection) {
                    measureCtx.font = 'bold ' + sectionFontSize + 'px ' + fontFamily;
                    w = measureCtx.measureText(parsed.text).width;
                } else if (parsed.isCage) {
                    measureCtx.font = 'bold ' + cageFontSize + 'px ' + fontFamily;
                    w = measureCtx.measureText(parsed.text).width;
                } else {
                    parsed.segments.forEach(function (seg) {
                        if (seg.bold) {
                            measureCtx.font = 'bold ' + fontSize + 'px ' + fontFamily;
                        } else {
                            measureCtx.font = fontSize + 'px ' + fontFamily;
                        }
                        w += measureCtx.measureText(seg.text).width;
                    });
                }
                if (w > maxLineWidth) maxLineWidth = w;
            });

            measureCtx.font = (fontSize - 6) + 'px ' + fontFamily;
            var footerStr = 'Laporan Sore • {{ $tanggal->format("d-m-Y") }}';
            var footerW = measureCtx.measureText(footerStr).width;
            if (footerW > maxLineWidth) maxLineWidth = footerW;

            var canvasWidth = Math.ceil(maxLineWidth + paddingX * 2);
            var totalHeight = paddingY;

            lines.forEach(function (line, idx) {
                var parsed = parsedLines[idx];
                if (idx === 0) {
                    totalHeight += headerFontSize + 14;
                } else if (line === '') {
                    totalHeight += lineHeight * 0.4;
                } else if (parsed.isHeader) {
                    totalHeight += headerFontSize + 10;
                } else if (parsed.isSection) {
                    totalHeight += sectionFontSize + 8;
                } else if (parsed.isCage) {
                    totalHeight += cageFontSize + 6;
                } else {
                    totalHeight += lineHeight;
                }
            });

            totalHeight += paddingY + 24;

            var canvas = document.createElement('canvas');
            canvas.width = canvasWidth * dpr;
            canvas.height = totalHeight * dpr;
            canvas.style.width = canvasWidth + 'px';
            canvas.style.height = totalHeight + 'px';

            var c = canvas.getContext('2d');
            c.scale(dpr, dpr);

            c.fillStyle = bgColor;
            c.fillRect(0, 0, canvasWidth, totalHeight);

            var y = paddingY;
            var x = paddingX;

            parsedLines.forEach(function (parsed, idx) {
                var line = lines[idx];

                if (idx === 0) {
                    c.font = 'bold ' + headerFontSize + 'px ' + fontFamily;
                    c.fillStyle = headerColor;
                    c.fillText(parsed.headerText || line, x, y + headerFontSize);
                    var hw = c.measureText(parsed.headerText || line).width;
                    if (parsed.headerDate) {
                        c.font = (fontSize - 2) + 'px ' + fontFamily;
                        c.fillStyle = '#555555';
                        c.fillText(' ' + parsed.headerDate, x + hw + 4, y + headerFontSize);
                    }
                    y += headerFontSize + 14;
                    return;
                }

                if (line === '') {
                    y += lineHeight * 0.4;
                    return;
                }

                if (parsed.isHeader) {
                    c.font = 'bold ' + headerFontSize + 'px ' + fontFamily;
                    c.fillStyle = headerColor;
                    c.fillText(parsed.text, x, y + headerFontSize);
                    y += headerFontSize + 10;
                    return;
                }

                if (parsed.isSection) {
                    c.font = 'bold ' + sectionFontSize + 'px ' + fontFamily;
                    c.fillStyle = sectionColor;
                    c.fillText(parsed.text, x, y + sectionFontSize);
                    y += sectionFontSize + 8;
                    return;
                }

                if (parsed.isCage) {
                    c.font = 'bold ' + cageFontSize + 'px ' + fontFamily;
                    c.fillStyle = cageColor;
                    c.fillText(parsed.text, x, y + cageFontSize);
                    y += cageFontSize + 6;
                    return;
                }

                var curX = x;
                parsed.segments.forEach(function (seg) {
                    if (seg.bold) {
                        c.font = 'bold ' + fontSize + 'px ' + fontFamily;
                        c.fillStyle = boldColor;
                    } else {
                        c.font = fontSize + 'px ' + fontFamily;
                        c.fillStyle = textColor;
                    }
                    c.fillText(seg.text, curX, y + fontSize);
                    curX += c.measureText(seg.text).width;
                });
                y += lineHeight;
            });

            c.font = (fontSize - 6) + 'px ' + fontFamily;
            c.fillStyle = footerColor;
            var timeStr = 'Laporan Sore • {{ $tanggal->format("d-m-Y") }}';
            var timeWidth = c.measureText(timeStr).width;
            c.fillText(timeStr, canvasWidth - paddingX - timeWidth, totalHeight - 12);

            var link = document.createElement('a');
            var locSlug = '{{ $locName }}'.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            var tglSlug = '{{ $tanggal->format("Y-m-d") }}';
            link.download = 'laporan-sore-' + locSlug + '-' + tglSlug + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        function parseLine(line) {
            if (!line) return { segments: [] };

            var headerMatch = line.match(/^\*([^*]+)\*\s+(.+)$/);
            if (headerMatch && !line.includes('•') && !line.includes('=') && !line.includes(':')) {
                return { isHeader: true, headerText: headerMatch[1], headerDate: headerMatch[2] };
            }

            if (/^\*SISA\s/.test(line) || /^\*CAMPURAN/.test(line) || /^\*KIRIM/.test(line) || /^\*STOCK/.test(line)) {
                return { isSection: true, text: line.replace(/\*/g, '') };
            }

            if (/^\*[^*]+\*$/.test(line.trim()) && !line.includes('•') && !line.includes('=')) {
                return { isCage: true, text: line.replace(/\*/g, '') };
            }

            var segments = [];
            var regex = /\*([^*]+)\*/g;
            var lastIdx = 0;
            var match;

            while ((match = regex.exec(line)) !== null) {
                if (match.index > lastIdx) {
                    segments.push({ text: line.substring(lastIdx, match.index), bold: false });
                }
                segments.push({ text: match[1], bold: true });
                lastIdx = regex.lastIndex;
            }
            if (lastIdx < line.length) {
                segments.push({ text: line.substring(lastIdx), bold: false });
            }

            return { segments: segments };
        }
    });
    </script>
    @endpush

    <style>
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    #btn-download-gambar:disabled { opacity: 0.7; cursor: wait; }

    /* Detail cage group card */
    .detail-cage-group:last-child {
        border-bottom: none !important;
    }
    .detail-tali-badge {
        font-size: 12px;
        color: var(--text-secondary);
        font-weight: 500;
        background: var(--bg);
        padding: 1px 8px;
        border-radius: var(--radius-badge);
    }
    .detail-konsep-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding-left: 24px;
    }
    .detail-konsep-item {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        line-height: 1.6;
    }
    .detail-bullet {
        color: var(--text-muted);
        font-weight: 700;
        margin-right: 2px;
    }
    .detail-konsep-name {
        font-weight: 600;
        color: var(--text);
    }
    .detail-plus {
        color: var(--text-muted);
        font-weight: 400;
        font-size: 12px;
    }
    .detail-item-tag {
        font-size: 12px;
        color: var(--primary);
        font-weight: 500;
        background: var(--primary-light);
        padding: 1px 6px;
        border-radius: 4px;
    }
    .detail-eq {
        color: var(--text-muted);
        font-weight: 400;
        margin: 0 2px;
    }
    .detail-jumlah {
        font-weight: 700;
        color: var(--text);
    }
    .detail-satuan {
        font-size: 11px;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .detail-konsep-item {
            font-size: 12px;
            gap: 3px;
        }
        .detail-konsep-list {
            padding-left: 16px;
        }
        .detail-item-tag {
            font-size: 11px;
        }
        #teks-container {
            font-size: 12px !important;
            padding: 14px !important;
        }
    }
    </style>
</x-layouts.dashboard>
