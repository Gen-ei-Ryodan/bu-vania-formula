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
                            <div class="label">Pembuat</div>
                            <select name="pembuat_id">
                                <option value="">- Pilih Pembuat -</option>
                                @foreach ($pembuats as $pembuat)
                                    <option value="{{ $pembuat->id }}" @selected((int) old('pembuat_id') === $pembuat->id)>{{ $pembuat->name }}</option>
                                @endforeach
                            </select>
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
                if ((getTotalWeightKg(row) + weight) > baseKg) {
                    alert('Total weight melebihi base weight (' + baseKg.toFixed(2) + ' kg)');
                    weightInput.value = '';
                    pctInput.value = '';
                    return;
                }
                pctInput.value = ((weight / baseKg) * 100).toFixed(2);
            } else {
                pctInput.value = '';
            }
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
            row.querySelector('[data-calc-percentage]').addEventListener('input', () => recalcRow(row));
            row.querySelector('[data-name="weight_unit_id"]').addEventListener('change', () => recalcRow(row));
            row.querySelector('[data-name="item_id"]').addEventListener('change', updateItemOptions);
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
