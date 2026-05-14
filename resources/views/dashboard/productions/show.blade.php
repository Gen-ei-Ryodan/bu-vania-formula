<x-layouts.dashboard :title="'Production: '.$production->name" :heading="'Production: '.$production->name">
    <div class="grid-4">
        <div class="card">
            <div class="muted">Concept / Resep</div>
            <div class="inline" style="align-items: center; gap: 8px;">
                <strong style="font-size: 18px;">{{ $production->concept?->name }}</strong>
                @if ($production->concept)
                    <a class="btn" href="{{ route('concepts.edit', $production->concept) }}">Edit</a>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="muted">Jenis</div>
            <strong style="font-size: 18px;">{{ ucfirst($production->production_type) }}</strong>
            @if ($production->production_type === 'pengobatan')
                <div>Hari ke-{{ $production->treatment_day }} ({{ $production->treatment_time }})</div>
            @endif
        </div>
        <div class="card">
            <div class="muted">Target (kg)</div>
            <strong style="font-size: 18px;">{{ number_format($production->target_weight_kg, 2) }}</strong>
        </div>
        <div class="card">
            <div class="muted">Durasi</div>
            <strong style="font-size: 18px;">{{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</strong>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Header Production</h2>
            <div class="actions">
                <a class="btn" href="{{ route('productions.pdf', $production) }}">PDF</a>
                <a class="btn" href="{{ route('productions.index') }}">Kembali</a>
                <form method="POST" action="{{ route('productions.destroy', $production) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Hapus</button>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <div class="grid-2">
                <div class="card"><div class="muted">Nama</div><strong>{{ $production->name }}</strong></div>
                <div class="card"><div class="muted">Lokasi</div><strong>{{ $production->location ?? '-' }}</strong></div>
                <div class="card"><div class="muted">Kandang</div><strong>{{ $production->cage ?? '-' }}</strong></div>
                <div class="card"><div class="muted">Start Date</div><strong>{{ $production->start_date?->format('d-m-Y') ?? '-' }}</strong></div>
                <div class="card"><div class="muted">Tanggal Campur</div><strong>{{ $production->mix_date?->format('d-m-Y') ?? '-' }}</strong></div>
                <div class="card"><div class="muted">Notes</div><strong>{{ $production->notes ?? '-' }}</strong></div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Snapshot Production Items (Auto Scaling dari Konsep)</h2>
            <div class="actions">
                @if ($production->items->isEmpty())
                    <form method="POST" action="{{ route('productions.generate', $production) }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">Generate Snapshot</button>
                    </form>
                @else
                    <span class="chip">Generated</span>
                @endif
            </div>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Weight (kg) — Hasil Scaling</th>
                        <th>Source</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($production->items as $row)
                        <tr>
                            <td>{{ $row->item?->name }}</td>
                            <td>{{ number_format($row->weight_kg, 2) }}</td>
                            <td><span class="chip">{{ $row->source }}</span></td>
                        </tr>
                    @endforeach
                    @if ($production->items->isEmpty())
                        <tr>
                            <td colspan="3" class="muted">Klik Generate Snapshot untuk membuat item dari konsep dengan auto-scaling ke target {{ number_format($production->target_weight_kg, 2) }} kg.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Mode Input</h2>
        </div>
        <div class="panel-body">
            <div class="inline" style="gap: 16px;">
                <label class="inline" style="align-items: center; gap: 6px; cursor: pointer;">
                    <input type="radio" name="input-mode" value="golongan" checked data-mode-toggle>
                    Golongan
                </label>
                <label class="inline" style="align-items: center; gap: 6px; cursor: pointer;">
                    <input type="radio" name="input-mode" value="tab" data-mode-toggle>
                    Tab (Split Batch)
                </label>
            </div>
        </div>
    </div>

    <div id="mode-golongan" class="panel">
        <div class="panel-header">
            <h2>Golongan</h2>
            <span class="chip">Sisa untuk Tab: {{ number_format($tabAvailableKg, 2) }} kg</span>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('productions.groups.store', $production) }}">
                @csrf
                <div class="inline">
                    <div class="field w-280">
                        <div class="label">Nama Golongan</div>
                        <input type="text" name="name" placeholder="Golongan 1">
                    </div>
                    <button class="btn btn-primary" type="submit">Tambah</button>
                </div>
            </form>

            <div class="divider"></div>

            <div class="stack">
                @foreach ($production->groups as $group)
                    <div class="panel">
                        <div class="panel-header">
                            <h2>{{ $group->name }}</h2>
                            <form method="POST" action="{{ route('groups.destroy', $group) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ route('groups.items.store', $group) }}" class="group-item-form">
                                @csrf
                                <div class="inline">
                                    <div class="field w-280">
                                        <div class="label">Item</div>
                                        <select name="item_id" class="item-select">
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field w-220">
                                        <div class="label">Weight (kg)</div>
                                        <input type="number" step="0.0001" name="weight_value" class="weight-input" placeholder="1">
                                    </div>
                                    <div class="field w-160">
                                        <div class="label">Unit</div>
                                        <select name="weight_unit_id">
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field" style="align-self: flex-end;">
                                        <label class="inline" style="align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" class="dosis-toggle" data-group-id="{{ $group->id }}">
                                            Dosis
                                        </label>
                                    </div>
                                    <button class="btn btn-primary" type="submit" style="align-self: flex-end;">Tambah</button>
                                </div>
                            </form>

                            <div class="divider"></div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Weight (kg)</th>
                                        <th>Dosis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group->items as $gi)
                                        <tr>
                                            <td>{{ $gi->item?->name }}</td>
                                            <td>{{ number_format($gi->weight_kg, 2) }}</td>
                                            <td>{!! $gi->is_dosis ? '<span class="chip">Dosis</span>' : '<span class="muted">Non</span>' !!}</td>
                                            <td>
                                                <form method="POST" action="{{ route('groups.items.destroy', $gi) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($group->items->isEmpty())
                                        <tr>
                                            <td colspan="4" class="muted">Belum ada item.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                @if ($production->groups->isEmpty())
                    <div class="muted">Belum ada golongan.</div>
                @endif
            </div>
        </div>
    </div>

    <div id="mode-tab" class="panel" style="display: none;">
        <div class="panel-header">
            <h2>Tab (Split Batch)</h2>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('productions.tabs.store', $production) }}">
                @csrf
                <div class="inline">
                    <div class="field w-220">
                        <div class="label">Nama Tab</div>
                        <input type="text" name="name" placeholder="Tab 1">
                    </div>
                    <div class="field w-220">
                        <div class="label">Ambil Berapa (kg)</div>
                        <input type="number" step="0.0001" name="input_weight_value" placeholder="1">
                    </div>
                    <div class="field w-160">
                        <div class="label">Unit</div>
                        <select name="input_weight_unit_id">
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit">Buat Tab</button>
                </div>
            </form>

            <div class="divider"></div>

            @if ($production->tabs->isNotEmpty())
                @php $cumulativeUsed = 0; @endphp
                <div class="card" style="margin-bottom: 12px;">
                    <div class="inline" style="gap: 24px;">
                        <div><strong>Split Batch:</strong></div>
                        @foreach ($production->tabs as $tab)
                            @php $cumulativeUsed += (float) $tab->input_weight_kg; @endphp
                            <div>
                                <span class="chip">{{ $tab->name }}: {{ number_format($tab->input_weight_kg, 2) }} kg</span>
                                <span class="chip">Sisa: {{ number_format($tab->remaining_weight_kg, 2) }} kg</span>
                            </div>
                        @endforeach
                        <div><span class="chip">Total Ambil: {{ number_format($cumulativeUsed, 2) }} kg</span></div>
                        <div><span class="chip">Sisa Global: {{ number_format($tabAvailableKg, 2) }} kg</span></div>
                    </div>
                </div>
            @endif

            <div class="stack">
                @foreach ($production->tabs as $tab)
                    <div class="panel">
                        <div class="panel-header">
                            <h2>{{ $tab->name }}</h2>
                            <span class="chip">Ambil: {{ number_format($tab->input_weight_kg, 2) }} kg</span>
                            <span class="chip">Sisa: {{ number_format($tab->remaining_weight_kg, 2) }} kg</span>
                            <form method="POST" action="{{ route('tabs.destroy', $tab) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ route('tabs.items.store', $tab) }}" class="tab-item-form">
                                @csrf
                                <div class="inline">
                                    <div class="field w-280">
                                        <div class="label">Item</div>
                                        <select name="item_id" class="item-select">
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field w-220">
                                        <div class="label">Weight (kg)</div>
                                        <input type="number" step="0.0001" name="weight_value" class="weight-input" placeholder="1">
                                    </div>
                                    <div class="field w-160">
                                        <div class="label">Unit</div>
                                        <select name="weight_unit_id">
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field" style="align-self: flex-end;">
                                        <label class="inline" style="align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" class="dosis-toggle" data-tab-id="{{ $tab->id }}">
                                            Dosis
                                        </label>
                                    </div>
                                    <button class="btn btn-primary" type="submit" style="align-self: flex-end;">Tambah</button>
                                </div>
                            </form>

                            <div class="divider"></div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Weight (kg)</th>
                                        <th>Dosis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tab->items as $ti)
                                        <tr>
                                            <td>{{ $ti->item?->name }}</td>
                                            <td>{{ number_format($ti->weight_kg, 2) }}</td>
                                            <td>{!! $ti->is_dosis ? '<span class="chip">Dosis</span>' : '<span class="muted">Non</span>' !!}</td>
                                            <td>
                                                <form method="POST" action="{{ route('tabs.items.destroy', $ti) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($tab->items->isEmpty())
                                        <tr>
                                            <td colspan="4" class="muted">Belum ada item.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                @if ($production->tabs->isEmpty())
                    <div class="muted">Belum ada Tab.</div>
                @endif
            </div>
        </div>
    </div>

    <div id="dosis-modal" class="modal-overlay" style="display: none;">
        <div class="modal" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Kalkulator Dosis</h3>
                <button type="button" class="btn" id="dosis-close">Tutup</button>
            </div>
            <div class="modal-body">
                <div class="card" style="background: #f9f9f9;">
                    <div class="grid-2">
                        <div class="field">
                            <div class="label">Item</div>
                            <input type="text" id="dosis-item-name" readonly>
                        </div>
                        <div class="field">
                            <div class="label">Target Produksi</div>
                            <input type="text" id="dosis-target" readonly value="{{ number_format($production->target_weight_kg, 2) }} kg">
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="grid-2">
                        <div class="field">
                            <div class="label">Berat Dosis</div>
                            <input type="number" step="0.0001" id="dosis-weight" placeholder="1">
                        </div>
                        <div class="field">
                            <div class="label">Satuan</div>
                            <select id="dosis-unit">
                                <option value="kg">kg</option>
                                <option value="gram">gram</option>
                                <option value="mg">mg</option>
                            </select>
                        </div>
                        <div class="field">
                            <div class="label">Per</div>
                            <input type="number" step="0.0001" id="dosis-per" placeholder="1" value="1">
                        </div>
                        <div class="field">
                            <div class="label">Satuan Per</div>
                            <select id="dosis-per-unit">
                                <option value="kg">kg</option>
                                <option value="gram">gram</option>
                                <option value="mg">mg</option>
                            </select>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="card" style="background: #e8f5e9; text-align: center;">
                        <div class="muted">Hasil Perhitungan:</div>
                        <strong style="font-size: 24px;" id="dosis-result">0 kg</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="dosis-close-btn">Batal</button>
                <button class="btn btn-primary" id="dosis-pakai">Pakai Hasil</button>
            </div>
        </div>
    </div>

    <style>
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1000; display: flex; align-items: center; justify-content: center; }
        .modal { background: #fff; border-radius: 8px; width: 100%; box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        .modal-header, .modal-footer { padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; }
        .modal-body { padding: 0 20px 16px; }
        .modal-footer { border-top: 1px solid #eee; gap: 8px; justify-content: flex-end; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        function switchMode(mode) {
            const panelGol = document.getElementById('mode-golongan');
            const panelTab = document.getElementById('mode-tab');
            if (mode === 'golongan') {
                panelGol.style.display = '';
                panelTab.style.display = 'none';
            } else {
                panelGol.style.display = 'none';
                panelTab.style.display = '';
            }
        }

        // track current mode to enable confirm on switch
        let currentMode = document.querySelector('[data-mode-toggle]:checked') ? document.querySelector('[data-mode-toggle]:checked').value : 'golongan';

        function panelHasUnsavedInputs(mode) {
            const panel = (mode === 'golongan') ? document.getElementById('mode-golongan') : document.getElementById('mode-tab');
            if (!panel) return false;
            // look for any weight inputs with values or any text inputs inside the panel
            const inputs = panel.querySelectorAll('input[type="number"], input[type="text"], textarea, select');
            for (let i = 0; i < inputs.length; i++) {
                const el = inputs[i];
                if (el.type === 'number' || el.type === 'text' || el.tagName.toLowerCase() === 'textarea') {
                    if (el.value && el.value.toString().trim() !== '') return true;
                } else if (el.tagName.toLowerCase() === 'select') {
                    if (el.selectedIndex > 0) return true;
                }
            }
            return false;
        }

        document.querySelectorAll('[data-mode-toggle]').forEach(function (radio) {
            radio.addEventListener('click', function (e) {
                const newMode = this.value;
                if (newMode === currentMode) return; // nothing to do

                // if current panel has data, ask confirmation
                if (panelHasUnsavedInputs(currentMode)) {
                    const ok = confirm('Anda sudah mengisi beberapa data. Mengganti mode akan menghapus input yang belum disimpan. Lanjutkan?');
                    if (!ok) {
                        // revert radio selection
                        e.preventDefault();
                        // re-check previous radio
                        document.querySelectorAll('[data-mode-toggle]').forEach(function (r) { r.checked = (r.value === currentMode); });
                        return;
                    } else {
                        // clear inputs in current panel
                        const panel = (currentMode === 'golongan') ? document.getElementById('mode-golongan') : document.getElementById('mode-tab');
                        if (panel) {
                            const inputsToClear = panel.querySelectorAll('input[type="number"], input[type="text"], textarea');
                            inputsToClear.forEach(function (inp) { inp.value = ''; });
                            const selects = panel.querySelectorAll('select');
                            selects.forEach(function (s) { s.selectedIndex = 0; });
                            const checkboxes = panel.querySelectorAll('input[type="checkbox"]');
                            checkboxes.forEach(function (c) { c.checked = false; });
                        }
                    }
                }

                // perform switch
                switchMode(newMode);
                currentMode = newMode;
            });
            if (radio.checked) {
                switchMode(radio.value);
            }
        });

        const dosisModal = document.getElementById('dosis-modal');
        const dosisItemName = document.getElementById('dosis-item-name');
        const dosisWeight = document.getElementById('dosis-weight');
        const dosisUnit = document.getElementById('dosis-unit');
        const dosisPer = document.getElementById('dosis-per');
        const dosisPerUnit = document.getElementById('dosis-per-unit');
        const dosisResult = document.getElementById('dosis-result');
        const dosisTarget = document.getElementById('dosis-target');
        let activeForm = null;
        let activeItemSelect = null;
        let isTabContext = false;
        let contextId = null;
        let pendingDosisToggle = null; // checkbox that triggered modal, not yet applied

        function openDosisModal(form, toggleCb) {
            activeForm = form;
            pendingDosisToggle = toggleCb;
            const itemSelect = form.querySelector('.item-select');
            const weightInput = form.querySelector('.weight-input');
            activeItemSelect = itemSelect;

            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            dosisItemName.value = selectedOption ? selectedOption.text : '';

            const groupId = toggleCb ? toggleCb.dataset.groupId : null;
            const tabId = toggleCb ? toggleCb.dataset.tabId : null;
            isTabContext = !!tabId;
            contextId = groupId || tabId;

            if (weightInput) {
                weightInput.disabled = true;
                weightInput.style.opacity = '0.5';
            }

            dosisWeight.value = '';
            dosisPer.value = '1';
            dosisResult.textContent = '0 kg';
            dosisModal.style.display = 'flex';
        }

        function closeDosis(keepChecked = false) {
            dosisModal.style.display = 'none';
            if (activeForm) {
                const weightInput = activeForm.querySelector('.weight-input');
                if (weightInput) {
                    weightInput.disabled = false;
                    weightInput.style.opacity = '1';
                }
                if (pendingDosisToggle && !keepChecked) pendingDosisToggle.checked = false;
                pendingDosisToggle = null;
            }
            activeForm = null;
        }

        document.querySelectorAll('.dosis-toggle').forEach(function (cb) {
            cb.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                openDosisModal(form, this);
            });
        });

        function calculateDosis() {
            const weight = parseFloat(dosisWeight.value) || 0;
            const per = parseFloat(dosisPer.value) || 1;
            let weightKg = weight;
            let perKg = per;

            if (dosisUnit.value === 'gram') weightKg = weight / 1000;
            else if (dosisUnit.value === 'mg') weightKg = weight / 1000000;

            if (dosisPerUnit.value === 'gram') perKg = per / 1000;
            else if (dosisPerUnit.value === 'mg') perKg = per / 1000000;

            let targetKg = {{ $production->target_weight_kg }};
            if (isTabContext && contextId) {
                const tabPanel = document.querySelector('[data-tab-id="' + contextId + '"]') ? document.querySelector('[data-tab-id="' + contextId + '"]') .closest('.panel') : null;
                if (tabPanel) {
                    const chipSpans = tabPanel.querySelectorAll('.chip');
                    chipSpans.forEach(function (chip) {
                        const text = chip.textContent;
                        const match = text.match(/Ambil:\s*([\d,.]+)/);
                        if (match) {
                            targetKg = parseFloat(match[1].replace(/,/g, '')) || targetKg;
                        }
                    });
                }
            }

            if (perKg > 0 && weightKg > 0) {
                const result = (weightKg / perKg) * targetKg;
                dosisResult.textContent = result.toFixed(4) + ' kg';
            } else {
                dosisResult.textContent = '0 kg';
            }
        }

        dosisWeight.addEventListener('input', calculateDosis);
        dosisUnit.addEventListener('change', calculateDosis);
        dosisPer.addEventListener('input', calculateDosis);
        dosisPerUnit.addEventListener('change', calculateDosis);

        document.getElementById('dosis-pakai').addEventListener('click', function () {
            const resultText = dosisResult.textContent;
            const resultKg = parseFloat(resultText) || 0;
            if (activeForm) {
                const weightInput = activeForm.querySelector('.weight-input');
                if (weightInput) {
                    weightInput.value = resultKg.toFixed(4);
                }
                // mark the checkbox as applied
                if (pendingDosisToggle) pendingDosisToggle.checked = true;
            }
            closeDosis(true);
        });

        document.getElementById('dosis-close').addEventListener('click', function () { closeDosis(false); });
        document.getElementById('dosis-close-btn').addEventListener('click', function () { closeDosis(false); });
        dosisModal.addEventListener('click', function (e) { if (e.target === this) closeDosis(false); });

        document.querySelectorAll('.item-select').forEach(function (sel) {
            sel.addEventListener('change', function () {
                const form = this.closest('form');
                const selectedOption = this.options[this.selectedIndex];
                if (dosisModal.style.display !== 'none') {
                    dosisItemName.value = selectedOption ? selectedOption.text : '';
                }
            });
        });
    });
    </script>
</x-layouts.dashboard>