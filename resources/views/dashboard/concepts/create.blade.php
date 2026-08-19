<x-layouts.dashboard title="Buat Konsep" heading="Buat Konsep">
    <div class="page-hero">
        <h1>Buat Konsep Baru</h1>
        <p>Buat resep pakan baru</p>
    </div>

    <form method="POST" action="{{ route('concepts.store') }}">
        @csrf
        <div class="content-section">
            <div class="form-card">
                <div class="form-card-header">
                    <h3>Informasi Konsep</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-grid">
                        <div class="field">
                            <div class="label">Nama</div>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Resep A" required>
                        </div>
                        <div class="field">
                            <div class="label">Konsep Dari</div>
                            <select name="pembuat_id">
                                <option value="">- Pilih Konsep Dari -</option>
                                @foreach ($pembuats as $pembuat)
                                    <option value="{{ $pembuat->id }}" @selected((int) old('pembuat_id') === $pembuat->id)>{{ $pembuat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <div class="label">Tanggal Mulai</div>
                            <input type="date" name="start_date" value="{{ old('start_date') }}">
                        </div>
                        <div class="field">
                            <div class="label">Keterangan</div>
                            <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Keterangan konsep...">
                        </div>
                        <div class="field form-grid-full">
                            <div class="label">Berat Dasar</div>
                            <div class="input-group" style="max-width: 400px;">
                                <input type="number" step="0.0001" name="base_weight_value" id="base-weight-value" value="{{ old('base_weight_value', 1) }}" placeholder="1">
                                <select name="base_weight_unit_id" id="base-weight-unit">
                                    @php($u = (int) old('base_weight_unit_id', $units->first()?->id ?? 0))
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" @selected((isset($u) && $u === $unit->id))>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="card" data-kombinasi>
                <div class="card-header">
                    <h3>Kombinasi Konsep (Opsional)</h3>
                    <button class="btn btn-primary btn-sm" data-kombinasi-add type="button">+ Tambah Konsep</button>
                </div>
                <div class="card-body">
                    <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 12px;">
                        Gabungkan item dari konsep yang sudah ada. Item yang sama akan dijumlahkan.
                    </p>
                    <div class="stack" data-kombinasi-list></div>
                    <template>
                        <div class="repeatable-row" data-kombinasi-row>
                            <div class="grid-form-row">
                                <div class="field" style="flex: 2;">
                                    <div class="label">Konsep</div>
                                    <select data-konsep-select>
                                        <option value="">- Pilih Konsep -</option>
                                        @foreach ($allConcepts as $c)
                                            <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field" style="flex: 1;">
                                    <div class="label">Persentase (%)</div>
                                    <input data-konsep-pct type="number" step="0.01" min="0.01" max="100" placeholder="50">
                                </div>
                                <div style="display: flex; align-items: center; justify-content: center; padding-top: 18px;">
                                    <button class="btn btn-danger btn-sm" data-kombinasi-remove type="button">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="card" data-repeatable>
                <div class="card-header">
                    <h3>Komposisi Item</h3>
                    <button class="btn btn-primary btn-sm" data-repeatable-add type="button">+ Tambah Baris</button>
                </div>
                <div class="card-body">
                    <div class="stack" data-repeatable-list></div>

                    <template>
                        <div class="repeatable-row" data-repeatable-row>
                            <div class="grid-form-row">
                                <div class="field">
                                    <div class="label">Item</div>
                                    <select data-name="item_id">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <div class="label">Percentage (%)</div>
                                    <input data-name="percentage" type="number" step="0.0001" readonly>
                                </div>
                                <div class="field">
                                    <div class="label">Berat</div>
                                    <input data-name="weight_value" type="number" step="0.0001" placeholder="1" data-calc-percentage>
                                </div>
                                <div class="field">
                                    <div class="label">Unit</div>
                                    <select data-name="weight_unit_id">
                                        <option value="">-</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $unit->conversion_to_kg == 1 ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <div class="label">Biaya</div>
                                    <div data-item-price class="field-hint">Rp0</div>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: center;">
                                    <button class="btn btn-danger btn-sm" data-repeatable-remove type="button">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="summary-box">
                <div class="summary-item">
                    <div class="label">Total Berat (kg)</div>
                    <div class="value" id="total-weight-display">0.0000</div>
                </div>
                <div class="summary-item">
                    <div class="label">Target Berat Dasar (kg)</div>
                    <div class="value" id="target-weight-display">0.0000</div>
                </div>
                <div class="summary-item">
                    <div class="label">Kurang (kg)</div>
                    <div class="value" id="remaining-weight-display" style="color: var(--danger);">0.0000</div>
                </div>
                <div class="summary-item">
                    <div class="label">Total Harga Resep</div>
                    <div class="value" id="total-price-display">Rp0</div>
                </div>
            </div>
        </div>

        <div class="content-section" style="display: flex; gap: 8px; justify-content: flex-end;">
            <a class="btn btn-ghost" href="{{ route('concepts.index') }}">Batal</a>
            <button class="btn btn-primary btn-lg" type="submit">Simpan Konsep</button>
        </div>
    </form>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const units = @json($unitsData ?? []);
        const items = @json($itemsData ?? []);
        const allConcepts = @json($allConcepts ?? []);
        const baseWeightInput = document.getElementById('base-weight-value');
        const baseWeightUnit = document.getElementById('base-weight-unit');

        function getUnitConversion(unitId) {
            if (!unitId) return 1;
            const unit = units[unitId];
            return parseFloat(typeof unit === 'object' ? (unit.conversion_to_kg || 1) : (unit || 1));
        }

        function getBaseKg() {
            return (parseFloat(baseWeightInput.value) || 0) * getUnitConversion(baseWeightUnit.value);
        }

        // ===== Kombinasi Konsep =====
        function getCombinedItems(baseKg) {
            const combined = {};
            document.querySelectorAll('[data-kombinasi-row]').forEach(row => {
                const conceptId = parseInt(row.querySelector('[data-konsep-select]').value);
                const pct = parseFloat(row.querySelector('[data-konsep-pct]').value);
                if (!conceptId || !pct || pct <= 0) return;
                const concept = allConcepts.find(c => c.id === conceptId);
                if (!concept || !concept.base_weight_kg || concept.base_weight_kg <= 0) return;

                const allocatedKg = baseKg * (pct / 100);
                concept.items.forEach(item => {
                    const itemProp = item.weight_kg / concept.base_weight_kg;
                    const finalWeight = itemProp * allocatedKg;
                    if (combined[item.item_id]) {
                        combined[item.item_id].weight_kg += finalWeight;
                    } else {
                        combined[item.item_id] = {
                            item_id: item.item_id,
                            item_name: item.item_name,
                            weight_kg: finalWeight,
                        };
                    }
                });
            });
            return Object.values(combined);
        }

        function isKombinasiActive() {
            let active = false;
            document.querySelectorAll('[data-kombinasi-row]').forEach(row => {
                const conceptId = parseInt(row.querySelector('[data-konsep-select]').value);
                const pct = parseFloat(row.querySelector('[data-konsep-pct]').value);
                if (conceptId && pct && pct > 0) active = true;
            });
            return active;
        }

        function applyCombinedItems() {
            if (!isKombinasiActive()) return;

            const baseKg = getBaseKg();
            if (baseKg <= 0) return;

            const combined = getCombinedItems(baseKg);
            const listContainer = document.querySelector('[data-repeatable-list]');
            const template = document.querySelector('[data-repeatable] template');

            let kgUnitId = null;
            for (const [id, conv] of Object.entries(units)) {
                const val = typeof conv === 'object' ? parseFloat(conv.conversion_to_kg || 1) : parseFloat(conv || 1);
                if (val === 1) { kgUnitId = id; break; }
            }

            // Clear existing rows
            listContainer.innerHTML = '';

            combined.forEach(item => {
                const clone = template.content.firstElementChild.cloneNode(true);
                listContainer.appendChild(clone);
                clone.querySelector('[data-name="item_id"]').value = item.item_id;
                clone.querySelector('[data-calc-percentage]').value = item.weight_kg.toFixed(4);
                if (kgUnitId) clone.querySelector('[data-name="weight_unit_id"]').value = kgUnitId;
                clone.querySelector('[data-calc-percentage]').dataset.autoFilled = '1';
                attachRowListeners(clone);
            });

            // Set name attributes for form submission (mirroring app.js refreshNames)
            refreshRowNames();
            // Attach remove button listeners
            listContainer.querySelectorAll('[data-repeatable-remove]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    btn.closest('[data-repeatable-row]').remove();
                    refreshRowNames();
                });
            });

            recalcAll();
            updateItemOptions();
        }

        function refreshRowNames() {
            const listContainer = document.querySelector('[data-repeatable-list]');
            listContainer.querySelectorAll('[data-repeatable-row]').forEach(function (row, index) {
                row.querySelectorAll('[data-name]').forEach(function (el) {
                    if (el.tagName !== 'INPUT' && el.tagName !== 'SELECT') return;
                    const base = el.getAttribute('data-name');
                    if (!base) return;
                    el.name = 'items[' + index + '][' + base + ']';
                });
            });
        }

        function setupKombinasiListeners(row) {
            if (row.dataset.kombinasiAttached === '1') return;
            row.dataset.kombinasiAttached = '1';
            row.querySelector('[data-konsep-select]').addEventListener('change', applyCombinedItems);
            row.querySelector('[data-konsep-pct]').addEventListener('input', applyCombinedItems);
        }

        // Kombinasi add/remove buttons
        const kombinasiContainer = document.querySelector('[data-kombinasi]');
        if (kombinasiContainer) {
            const addBtn = kombinasiContainer.querySelector('[data-kombinasi-add]');
            const listEl = kombinasiContainer.querySelector('[data-kombinasi-list]');
            const tpl = kombinasiContainer.querySelector('template');

            addBtn.addEventListener('click', function () {
                const clone = tpl.content.firstElementChild.cloneNode(true);
                listEl.appendChild(clone);
                setupKombinasiListeners(clone);
            });

            listEl.addEventListener('click', function (e) {
                const btn = e.target.closest('[data-kombinasi-remove]');
                if (btn) {
                    btn.closest('[data-kombinasi-row]').remove();
                    if (!isKombinasiActive()) {
                        document.querySelectorAll('[data-calc-percentage]').forEach(el => delete el.dataset.autoFilled);
                    }
                    applyCombinedItems();
                }
            });
        }

        // ===== Komposisi Item =====
        function getTotalWeightKg(excludeRow) {
            let total = 0;
            document.querySelectorAll('[data-repeatable-row]').forEach(row => {
                if (row === excludeRow) return;
                const weight = parseFloat(row.querySelector('[data-calc-percentage]').value) || 0;
                const conv = getUnitConversion(row.querySelector('[data-name="weight_unit_id"]').value);
                total += weight * conv;
            });
            return total;
        }

        function recalcRow(row) {
            const weightInput = row.querySelector('[data-calc-percentage]');
            const pctInput = row.querySelector('[data-name="percentage"]');
            const unitSelect = row.querySelector('[data-name="weight_unit_id"]');
            const weight = (parseFloat(weightInput.value) || 0) * getUnitConversion(unitSelect.value);
            const baseKg = getBaseKg();

            if (baseKg > 0 && weight > 0) {
                pctInput.value = ((weight / baseKg) * 100).toFixed(2);
            } else {
                pctInput.value = '';
            }

            const item = items[row.querySelector('[data-name="item_id"]').value];
            const cost = item && item.price_unit_value > 0 && item.price_unit_conversion_to_kg > 0
                ? (item.price / (item.price_unit_value * item.price_unit_conversion_to_kg)) * weight
                : 0;
            row.dataset.itemCost = cost;
            row.dataset.itemCost = cost;
            row.querySelector('[data-item-price]').textContent = 'Rp' + Math.round(cost).toLocaleString('id-ID');
        }

        function recalcAll() {
            document.querySelectorAll('[data-repeatable-row]').forEach(recalcRow);
            updateWeightInfo();
        }

        function updateWeightInfo() {
            const baseKg = getBaseKg();
            let totalKg = 0;
            document.querySelectorAll('[data-repeatable-row]').forEach(row => {
                const weight = parseFloat(row.querySelector('[data-calc-percentage]').value) || 0;
                const conv = getUnitConversion(row.querySelector('[data-name="weight_unit_id"]').value);
                totalKg += weight * conv;
            });
            const remaining = baseKg - totalKg;
            document.getElementById('target-weight-display').textContent = baseKg.toFixed(4);
            document.getElementById('total-weight-display').textContent = totalKg.toFixed(4);
            document.getElementById('remaining-weight-display').textContent = remaining >= 0 ? remaining.toFixed(4) : '0.0000';
            let totalPrice = 0;
            document.querySelectorAll('[data-repeatable-row]').forEach(row => {
                totalPrice += parseFloat(row.dataset.itemCost || 0);
            });
            document.getElementById('total-price-display').textContent = 'Rp' + totalPrice.toLocaleString('id-ID');
        }

        function updateItemOptions() {
            const selectedIds = new Set();
            const selects = document.querySelectorAll('[data-repeatable-row] select[data-name="item_id"]');
            selects.forEach(sel => { if (sel.value) selectedIds.add(sel.value); });
            selects.forEach(sel => {
                const val = sel.value;
                sel.querySelectorAll('option').forEach(opt => {
                    opt.disabled = !!(opt.value && selectedIds.has(opt.value) && opt.value !== val);
                });
            });
        }

        function attachRowListeners(row) {
            if (row.dataset.listenerAttached === '1') return;
            row.dataset.listenerAttached = '1';
            const weightInput = row.querySelector('[data-calc-percentage]');
            weightInput.addEventListener('input', recalcAll);
            // If user manually edits an auto-filled row, clear autoFilled flag
            weightInput.addEventListener('input', function () {
                delete this.dataset.autoFilled;
            });
            row.querySelector('[data-name="weight_unit_id"]').addEventListener('change', recalcAll);
            row.querySelector('[data-name="item_id"]').addEventListener('change', () => { updateItemOptions(); recalcAll(); });
        }

        baseWeightInput?.addEventListener('input', recalcAll);
        baseWeightUnit?.addEventListener('change', recalcAll);

        const observer = new MutationObserver(() => {
            document.querySelectorAll('[data-repeatable-row]').forEach(attachRowListeners);
            recalcAll();
            updateItemOptions();
        });
        const list = document.querySelector('[data-repeatable-list]');
        if (list) observer.observe(list, { childList: true });
        document.querySelectorAll('[data-repeatable-row]').forEach(attachRowListeners);
        recalcAll();
        updateItemOptions();

        // Ensure name attributes are set for default rows added by app.js
        refreshRowNames();

        document.querySelector('form')?.addEventListener('submit', function (e) {
            let totalPct = 0;
            document.querySelectorAll('[data-repeatable-row]').forEach(row => {
                totalPct += parseFloat(row.querySelector('[data-name="percentage"]').value) || 0;
            });
            if (document.querySelectorAll('[data-repeatable-row]').length > 0 && Math.abs(totalPct - 100) > 0.01) {
                e.preventDefault();
                alert('Total persen konsep harus 100%. Saat ini: ' + totalPct.toFixed(2) + '%');
            }
        });
    });
    </script>
    @endpush
</x-layouts.dashboard>
