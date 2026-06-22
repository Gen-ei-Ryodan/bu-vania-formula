<x-layouts.dashboard title="Buat Laporan Sore" heading="Buat Laporan Sore">
    <div class="panel">
        <div class="panel-header">
            <h2>Header Laporan</h2>
            <a class="btn" href="{{ route('laporan-sore.index') }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('laporan-sore.store') }}" id="laporan-sore-form">
                @csrf

                <div class="grid-2">
                    <div class="field">
                        <div class="label">Lokasi <span class="text-danger">*</span></div>
                        <select name="location_id" id="location-id" required>
                            <option value="">- Pilih Lokasi -</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @selected((int) old('location_id') === $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <div class="label">Tanggal Laporan <span class="text-danger">*</span></div>
                        <input type="date" name="tanggal" id="tanggal-laporan" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        $sectionColors = [
            'sisa_kemarin' => ['label' => 'Sisa Kemarin', 'color' => '#F59E0B', 'desc' => 'Stok dari hari sebelumnya (H-1)'],
            'campuran_hari_ini' => ['label' => 'Campuran Hari Ini', 'color' => '#3B82F6', 'desc' => 'Hasil campuran pada hari laporan'],
            'kirim_hari_ini' => ['label' => 'Kirim Hari Ini', 'color' => '#10B981', 'desc' => 'Barang yang dikirim pada hari laporan'],
            'stock' => ['label' => 'Stock', 'color' => '#8B5CF6', 'desc' => 'Stok akhir yang tersedia'],
        ];
    @endphp

    @foreach ($sectionColors as $sectionKey => $sec)
    <div class="panel" data-section="{{ $sectionKey }}">
        <div class="panel-header" style="border-left: 4px solid {{ $sec['color'] }};">
            <div>
                <h2>{{ $sec['label'] }}</h2>
                <span class="section-desc">{{ $sec['desc'] }}</span>
                @if ($sectionKey === 'sisa_kemarin')
                    <span id="sisa-kemarin-date" class="badge badge-warning"></span>
                @endif
            </div>
            <button class="btn btn-primary" type="button" data-add-cage>+ Tambah Kandang</button>
        </div>
        <div class="panel-body">
            <div class="stack" data-cage-list></div>

            <template data-cage-template>
                <div class="cage-card" data-cage-row>
                    <div class="cage-card-header">
                        <div class="cage-card-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
                            <span data-cage-label>Kandang Baru</span>
                        </div>
                        <button class="btn btn-sm btn-danger" type="button" data-remove-cage>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Hapus
                        </button>
                    </div>
                    <div class="cage-card-body">
                        <div class="grid-2" style="margin-bottom: 14px;">
                            <div class="field">
                                <div class="label">Kandang</div>
                                <select data-cage-select>
                                    <option value="">- Pilih Kandang -</option>
                                    @foreach ($locations as $location)
                                        @php $locCages = $location->cages->sortBy('name'); @endphp
                                        @if ($locCages->isNotEmpty())
                                            <optgroup label="{{ $location->name }}">
                                            @foreach ($locCages as $cage)
                                                <option value="{{ $cage->id }}" data-location-id="{{ $location->id }}">{{ $cage->name }}</option>
                                            @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <div class="label">Nama Tali</div>
                                <input type="text" data-tali-input placeholder="Contoh: Tali Hijau">
                            </div>
                        </div>

                        <div class="konsep-toolbar">
                            <strong>Detail Konsep</strong>
                            <button class="btn btn-sm btn-primary" type="button" data-add-konsep>+ Tambah Konsep</button>
                        </div>

                        <div class="stack" data-konsep-list style="gap: 8px;"></div>

                        <template data-konsep-template>
                            <div class="konsep-row" data-konsep-row>
                                <div class="konsep-row-grid">
                                    <div class="field">
                                        <div class="label">Konsep <span class="text-danger">*</span></div>
                                        <select data-konsep-select required>
                                            <option value="">- Pilih Konsep -</option>
                                            @foreach ($konseps as $konsep)
                                                <option value="{{ $konsep->id }}">{{ $konsep->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <div class="label">Item Tambahan</div>
                                        <select data-item-select multiple size="3" style="min-height: 80px;">
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="field-hint">Tahan Ctrl/Cmd untuk memilih lebih dari satu</div>
                                    </div>
                                    <div class="field w-sm">
                                        <div class="label">Jumlah <span class="text-danger">*</span></div>
                                        <input type="number" step="0.01" min="0" data-jumlah-input placeholder="0" required>
                                    </div>
                                    <div class="field w-sm">
                                        <div class="label">Satuan <span class="text-danger">*</span></div>
                                        <input type="text" data-satuan-input placeholder="Contoh: Zak" required>
                                    </div>
                                    <div class="konsep-row-actions">
                                        <button class="btn btn-sm btn-danger" type="button" data-remove-konsep>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div class="empty-state" data-empty-cage style="display:none;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--text-muted);"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                <p style="margin: 5px 0 0;">Klik <strong>+ Tambah Kandang</strong> untuk memulai</p>
            </div>
        </div>
    </div>
    @endforeach

    <div class="actions">
        <a class="btn btn-ghost" href="{{ route('laporan-sore.index') }}">Batal</a>
        <button class="btn btn-primary btn-lg" type="submit" id="btn-submit">Simpan Laporan</button>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const locationSelect = document.getElementById('location-id');
        const tanggalInput = document.getElementById('tanggal-laporan');
        const sisaKemarinEl = document.getElementById('sisa-kemarin-date');

        // Update H-1 date for Sisa Kemarin
        function updateSisaKemarin() {
            if (!sisaKemarinEl || !tanggalInput.value) return;
            const d = new Date(tanggalInput.value);
            d.setDate(d.getDate() - 1);
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            sisaKemarinEl.textContent = 'Tanggal H-1: ' + dd + '-' + mm + '-' + yyyy;
            sisaKemarinEl.style.display = 'inline-flex';
        }
        tanggalInput.addEventListener('change', updateSisaKemarin);
        updateSisaKemarin();

        // Filter cages by location
        function filterCages() {
            const selectedLocation = locationSelect.value;
            document.querySelectorAll('[data-cage-select] option, [data-cage-select] optgroup').forEach(el => {
                if (el.tagName === 'OPTION' && el.value === '') return;
                const locId = el.dataset.locationId || el.parentElement?.dataset?.locationId;
                if (!locId) return;
                el.style.display = (!selectedLocation || locId === selectedLocation) ? '' : 'none';
            });
            document.querySelectorAll('[data-cage-select]').forEach(sel => {
                const selectedOpt = sel.options[sel.selectedIndex];
                if (selectedOpt && selectedOpt.style.display === 'none') {
                    sel.value = '';
                }
            });
        }
        locationSelect.addEventListener('change', filterCages);

        // Initialize each section
        document.querySelectorAll('[data-section]').forEach(section => {
            const sectionKey = section.dataset.section;
            const cageList = section.querySelector('[data-cage-list]');
            const cageTemplate = section.querySelector('[data-cage-template]');
            const addCageBtn = section.querySelector('[data-add-cage]');
            const emptyState = section.querySelector('[data-empty-cage]');

            function toggleEmptyState() {
                if (emptyState) {
                    const hasCages = cageList.querySelectorAll('[data-cage-row]').length > 0;
                    emptyState.style.display = hasCages ? 'none' : '';
                }
            }

            function addCageRow() {
                const clone = cageTemplate.content.firstElementChild.cloneNode(true);
                cageList.appendChild(clone);
                setupCageRow(clone);
                filterCages();
                toggleEmptyState();
            }

            function setupCageRow(cageRow) {
                if (cageRow.dataset.attached) return;
                cageRow.dataset.attached = '1';

                const cageSelect = cageRow.querySelector('[data-cage-select]');
                const cageLabel = cageRow.querySelector('[data-cage-label]');
                const taliInput = cageRow.querySelector('[data-tali-input]');
                const konsepList = cageRow.querySelector('[data-konsep-list]');
                const konsepTemplate = cageRow.querySelector('[data-konsep-template]');
                const addKonsepBtn = cageRow.querySelector('[data-add-konsep]');
                const removeCageBtn = cageRow.querySelector('[data-remove-cage]');

                // Update cage label on select
                cageSelect.addEventListener('change', function () {
                    const opt = cageSelect.options[cageSelect.selectedIndex];
                    cageLabel.textContent = opt.value ? (opt.text || 'Kandang Baru') : 'Kandang Baru';
                });

                // Setup first cage row
                cageSelect.addEventListener('change', updateAllCageNumbers);

                // Remove
                removeCageBtn.addEventListener('click', function () {
                    cageRow.remove();
                    updateAllCageNumbers();
                    toggleEmptyState();
                });

                function addKonsepRow() {
                    const clone = konsepTemplate.content.firstElementChild.cloneNode(true);
                    konsepList.appendChild(clone);
                    setupKonsepRow(clone);
                }

                function setupKonsepRow(konsepRow) {
                    if (konsepRow.dataset.attached) return;
                    konsepRow.dataset.attached = '1';
                    konsepRow.querySelector('[data-remove-konsep]').addEventListener('click', function () {
                        konsepRow.remove();
                    });
                }

                addKonsepBtn.addEventListener('click', addKonsepRow);

                // Auto-add first konsep
                if (konsepList.children.length === 0) {
                    addKonsepRow();
                }
            }

            function updateAllCageNumbers() {
                const rows = cageList.querySelectorAll('[data-cage-row]');
                rows.forEach((row, i) => {
                    const nameInput = row.querySelector('[data-nama-input]');
                });
            }

            addCageBtn.addEventListener('click', addCageRow);
            toggleEmptyState();
        });

        // Submit form
        const form = document.getElementById('laporan-sore-form');
        const submitBtn = document.getElementById('btn-submit');
        submitBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const locId = locationSelect.value;
            const tanggal = tanggalInput.value;
            if (!locId) { alert('Pilih lokasi terlebih dahulu.'); return; }
            if (!tanggal) { alert('Pilih tanggal laporan.'); return; }

            const sections = [];

            document.querySelectorAll('[data-section]').forEach(section => {
                const sectionType = section.dataset.section;
                const rows = [];

                section.querySelectorAll('[data-cage-row]').forEach(cageRow => {
                    const cageSelect = cageRow.querySelector('[data-cage-select]');
                    const taliInput = cageRow.querySelector('[data-tali-input]');
                    const details = [];

                    cageRow.querySelectorAll('[data-konsep-row]').forEach(konsepRow => {
                        const konsepSelect = konsepRow.querySelector('[data-konsep-select]');
                        const itemSelect = konsepRow.querySelector('[data-item-select]');
                        const jumlahInput = konsepRow.querySelector('[data-jumlah-input]');
                        const satuanInput = konsepRow.querySelector('[data-satuan-input]');

                        const konsepId = konsepSelect.value;
                        const jumlah = jumlahInput.value;
                        const satuan = satuanInput.value;

                        if (!konsepId || !jumlah || !satuan) {
                            return; // skip incomplete rows
                        }

                        const selectedItems = [];
                        for (const opt of itemSelect.options) {
                            if (opt.selected && opt.value) selectedItems.push(opt.value);
                        }

                        details.push({
                            konsep_id: konsepId,
                            item_ids: selectedItems,
                            jumlah: jumlah,
                            satuan: satuan,
                        });
                    });

                    if (details.length === 0) return;

                    rows.push({
                        cage_id: cageSelect.value || null,
                        nama_tali: taliInput.value || null,
                        details: details,
                    });
                });

                if (rows.length > 0) {
                    sections.push({ type: sectionType, rows: rows });
                }
            });

            if (sections.length === 0) {
                alert('Minimal satu section harus diisi.');
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('location_id', locId);
            formData.append('tanggal', tanggal);
            formData.append('sections_data', JSON.stringify(sections));

            fetch('{{ route('laporan-sore.store') }}', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            })
            .then(r => {
                if (r.redirected) { window.location.href = r.url; return; }
                return r.json().then(data => { throw data; });
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        });
    });
    </script>
    @endpush

    <style>
    .section-desc {
        display: block;
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .text-danger { color: var(--danger); }
    .field-hint {
        font-size: 10px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .w-sm { flex: 0.4 !important; min-width: 100px !important; }

    /* Cage card */
    .cage-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,.03);
    }
    .cage-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: var(--card-alt);
        border-bottom: 1px solid var(--border);
    }
    .cage-card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 14px;
        color: var(--text);
    }
    .cage-card-title svg { color: var(--text-muted); }
    .cage-card-body { padding: 16px; }

    /* Konsep row */
    .konsep-row {
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }
    .konsep-row-grid {
        display: flex;
        gap: 10px;
        align-items: end;
        flex-wrap: wrap;
        padding: 12px 14px;
        background: var(--card-alt);
    }
    .konsep-row-grid .field { margin: 0; flex: 1; min-width: 130px; }
    .konsep-row-actions {
        display: flex;
        align-items: end;
        padding-bottom: 1px;
    }

    @media (max-width: 768px) {
        .konsep-row-grid { flex-direction: column; }
        .konsep-row-grid .field, .konsep-row-grid .w-sm { min-width: 0; width: 100%; flex: none; }
        .cage-card-header { flex-direction: column; align-items: flex-start; gap: 8px; }
    }
    </style>
</x-layouts.dashboard>
