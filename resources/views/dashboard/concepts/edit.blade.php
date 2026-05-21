<x-layouts.dashboard title="Edit Konsep" heading="Edit Konsep">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Konsep</h2>
            <a class="btn" href="{{ route('concepts.show', $concept) }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('concepts.update', $concept) }}">
                @csrf
                @method('PUT')
                <div class="grid-2">
                    <div class="field">
                        <div class="label">Nama</div>
                        <input type="text" name="name" value="{{ old('name', $concept->name) }}" placeholder="Resep A">
                    </div>
                    <div class="field">
                        <div class="label">Berat Dasar</div>
                        <div style="display: grid; grid-template-columns: 1fr 120px; gap: 10px;">
                            <input type="number" step="0.0001" name="base_weight_value" id="base-weight-value" value="{{ old('base_weight_value', $concept->base_weight_kg) }}" placeholder="1">
                            <select name="base_weight_unit_id" id="base-weight-unit">
                                @php($u = (int) old('base_weight_unit_id', 0))
                                @foreach ($units as $unit)
                                    @if ($unit->conversion_to_kg == 1)
                                        @php($u = $u ?: $unit->id)
                                    @endif
                                    <option value="{{ $unit->id }}" @selected((isset($u) && $u === $unit->id))>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                </div>

                <div class="divider"></div>

                <div class="panel" data-repeatable>
                    <div class="panel-header">
                        <h2>Komposisi Item</h2>
                        <button class="btn btn-primary" data-repeatable-add type="button">Tambah Baris</button>
                    </div>
                    <div class="panel-body">
                        <div class="stack" data-repeatable-list></div>

<template>
                            <div class="card" data-repeatable-row>
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
                                    <div style="display: flex; align-items: center; justify-content: center; padding-bottom: 2px;">
                                        <button class="btn btn-danger" data-repeatable-remove type="button">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Update Konsep</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const units = @json($unitsData ?? []);

        const baseWeightInput = document.getElementById('base-weight-value');
        const baseWeightUnit = document.getElementById('base-weight-unit');

        function getUnitConversion(unitId) {
            if (!unitId) return 1;

            const unit = units[unitId];

            if (typeof unit === 'object') {
                return parseFloat(unit.conversion_to_kg || 1);
            }

            return parseFloat(unit || 1);
        }

        function getBaseKg() {
            const value = parseFloat(baseWeightInput.value) || 0;
            const conv = getUnitConversion(baseWeightUnit.value);

            return value * conv;
        }

        function getTotalWeightKg(excludeRow) {
            let total = 0;

            document.querySelectorAll('[data-repeatable-row]').forEach(row => {
                if (row === excludeRow) return;

                const weightInput = row.querySelector('[data-calc-percentage]');
                const unitSelect = row.querySelector('[data-name="weight_unit_id"]');

                const weight = parseFloat(weightInput.value) || 0;
                const conv = getUnitConversion(unitSelect.value);

                total += weight * conv;
            });

            return total;
        }

        function recalcRow(row) {
            const weightInput = row.querySelector('[data-calc-percentage]');
            const pctInput = row.querySelector('[data-name="percentage"]');
            const unitSelect = row.querySelector('[data-name="weight_unit_id"]');

            const weight = parseFloat(weightInput.value) || 0;
            const conv = getUnitConversion(unitSelect.value);

            const weightKg = weight * conv;
            const baseKg = getBaseKg();

            if (baseKg > 0 && weightKg > 0) {
                const otherTotalKg = getTotalWeightKg(row);
                const allInTotal = otherTotalKg + weightKg;

                if (allInTotal > baseKg) {
                    alert('Total weight melebihi base weight (' + baseKg.toFixed(2) + ' kg)');

                    weightInput.value = '';
                    pctInput.value = '';

                    return;
                }

                const pct = ((weightKg / baseKg) * 100).toFixed(2);
                pctInput.value = pct;
            } else {
                pctInput.value = '';
            }
        }

        function recalcAll() {
            const rows = document.querySelectorAll('[data-repeatable-row]');

            rows.forEach(row => {
                recalcRow(row);
            });
        }

        function attachRowListeners(row) {
            if (row.dataset.listenerAttached === '1') return;

            row.dataset.listenerAttached = '1';

            const weightInput = row.querySelector('[data-calc-percentage]');
            const unitSelect = row.querySelector('[data-name="weight_unit_id"]');

            if (weightInput) {
                weightInput.addEventListener('input', function () {
                    recalcRow(row);
                });
            }

            if (unitSelect) {
                unitSelect.addEventListener('change', function () {
                    recalcRow(row);
                });
            }
        }

        if (baseWeightInput) {
            baseWeightInput.addEventListener('input', recalcAll);
        }

        if (baseWeightUnit) {
            baseWeightUnit.addEventListener('change', recalcAll);
        }

        const observer = new MutationObserver(function () {
            document.querySelectorAll('[data-repeatable-row]').forEach(row => {
                attachRowListeners(row);
            });

            recalcAll();
        });

        const list = document.querySelector('[data-repeatable-list]');

        if (list) {
            observer.observe(list, { childList: true });
        }

        document.querySelectorAll('[data-repeatable-row]').forEach(row => {
            attachRowListeners(row);
        });

        // Prefill existing items
        const existingItems = @json($concept->items);
        const listContainer = document.querySelector('[data-repeatable-list]');
        const template = document.querySelector('[data-repeatable] template');

        let kgUnitId = null;
        for (const [id, conv] of Object.entries(units)) {
            const val = typeof conv === 'object' ? parseFloat(conv.conversion_to_kg || 1) : parseFloat(conv || 1);
            if (val === 1) { kgUnitId = id; break; }
        }

        existingItems.forEach(function (item) {
            const clone = template.content.firstElementChild.cloneNode(true);
            listContainer.appendChild(clone);

            const itemSelect = clone.querySelector('[data-name="item_id"]');
            const pctInput = clone.querySelector('[data-name="percentage"]');
            const weightInput = clone.querySelector('[data-calc-percentage]');
            const unitSelect = clone.querySelector('[data-name="weight_unit_id"]');

            itemSelect.value = item.item_id;
            pctInput.value = item.percentage;
            weightInput.value = item.weight_kg;
            if (kgUnitId) unitSelect.value = kgUnitId;

            attachRowListeners(clone);
        });

        recalcAll();

        // Validasi submit
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const rows = document.querySelectorAll('[data-repeatable-row]');
                let totalPct = 0;

                rows.forEach(row => {
                    const pctInput = row.querySelector('[data-name="percentage"]');
                    totalPct += parseFloat(pctInput.value) || 0;
                });

                if (rows.length > 0 && Math.abs(totalPct - 100) > 0.01) {
                    e.preventDefault();
                    alert('Total persen konsep harus 100%. Saat ini: ' + totalPct.toFixed(2) + '%');
                }
            });
        }

    });
    </script>
    @endpush
</x-layouts.dashboard>
