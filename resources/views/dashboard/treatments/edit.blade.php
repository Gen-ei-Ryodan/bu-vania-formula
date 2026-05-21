<x-layouts.dashboard title="Edit Pengobatan" heading="Edit Pengobatan">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Pengobatan</h2>
            <a class="btn" href="{{ route('treatments.show', $production) }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('treatments.update', $production) }}">
                @csrf
                @method('PUT')
                <div class="grid-2">
                    <div class="field">
                        <div class="label">Nama</div>
                        <input type="text" name="name" value="{{ old('name', $production->name) }}" placeholder="Pengobatan April">
                    </div>
                    <div class="field">
                        <div class="label">Tanggal Campur</div>
                        <input type="date" name="mix_date" value="{{ old('mix_date', $production->mix_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="field">
                        <div class="label">Lokasi</div>
                        <input type="text" name="location" value="{{ old('location', $production->location) }}" placeholder="Lokasi A">
                    </div>
                    <div class="field">
                        <div class="label">Kandang</div>
                        <input type="text" name="cage" value="{{ old('cage', $production->cage) }}" placeholder="Kandang 1">
                    </div>
                    <div class="field">
                        <div class="label">Hari Pengobatan Ke</div>
                        <input type="number" name="treatment_day" value="{{ old('treatment_day', $production->treatment_day) }}" min="1" placeholder="1">
                    </div>
                    <div class="field">
                        <div class="label">Waktu Pengobatan</div>
                        <select name="treatment_time">
                            <option value="">Pilih Waktu</option>
                            <option value="pagi" @selected(old('treatment_time', $production->treatment_time) === 'pagi')>Pagi</option>
                            <option value="siang" @selected(old('treatment_time', $production->treatment_time) === 'siang')>Siang</option>
                            <option value="malam" @selected(old('treatment_time', $production->treatment_time) === 'malam')>Malam</option>
                            <option value="full" @selected(old('treatment_time', $production->treatment_time) === 'full')>Full</option>
                        </select>
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <div class="label">Konsep (Resep Dasar)</div>
                        <select name="concept_id" id="concept-select">
                            @php
                                $c = (int) old('concept_id', $production->concept_id);
                            @endphp
                            @foreach ($concepts as $concept)
                                <option value="{{ $concept->id }}" @selected($c === $concept->id)>{{ $concept->name }}</option>
                            @endforeach
                        </select>
                        <div id="concept-preview" class="stack" style="margin-top: 8px;"></div>
                    </div>
                    <div class="field">
                        <div class="label">Target Berat</div>
                        <div style="display: grid; grid-template-columns: 1fr 120px; gap: 10px;">
                            <input type="number" step="0.0001" name="target_weight_value" value="{{ old('target_weight_value', $production->target_weight_kg) }}" placeholder="1" id="target-weight-value">
                            <select name="target_weight_unit_id" id="target-weight-unit">
                                @php($u = (int) old('target_weight_unit_id', 0))
                                @foreach ($units as $unit)
                                    @if ($unit->conversion_to_kg == 1)
                                        @php($u = $u ?: $unit->id)
                                    @endif
                                    <option value="{{ $unit->id }}" data-conv="{{ $unit->conversion_to_kg }}" @selected((isset($u) && $u === $unit->id))>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <div class="label">Tgl Mulai</div>
                        <input type="date" name="start_date" value="{{ old('start_date', $production->start_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="field">
                        <div class="label">Durasi (hari)</div>
                        <div class="inline">
                            <input type="number" name="duration_days" value="{{ old('duration_days', $production->duration_days) }}" min="1" placeholder="20" id="duration-days" style="width: 140px;">
                            <label class="inline" style="align-items: center;">
                                <input type="checkbox" name="is_forever" value="1" @checked(old('is_forever', $production->is_forever)) id="is-forever">
                                Selamanya
                            </label>
                        </div>
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <div class="label">Catatan</div>
                        <textarea name="notes">{{ old('notes', $production->notes) }}</textarea>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Update Pengobatan</button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const durationInput = document.getElementById('duration-days');
        const foreverCheck = document.getElementById('is-forever');
        const conceptSelect = document.getElementById('concept-select');
        const conceptPreview = document.getElementById('concept-preview');
        const targetWeightInput = document.getElementById('target-weight-value');
        const targetWeightUnit = document.getElementById('target-weight-unit');

        const concepts = @json($conceptsData ?? []);

        function fmtNum(val) {
            const n = parseFloat(val);
            if (isNaN(n)) return '0';
            return n % 1 === 0 ? n.toFixed(0) : n.toFixed(2);
        }

        function getTargetKg() {
            const val = parseFloat(targetWeightInput.value) || 0;
            const conv = parseFloat(targetWeightUnit.options[targetWeightUnit.selectedIndex]?.dataset?.conv) || 1;
            return val * conv;
        }

        function showConceptPreview(id) {
            const data = concepts[id];
            if (!data) { conceptPreview.innerHTML = ''; return; }
            const targetKg = getTargetKg();
            let html = '<div class="card" style="padding: 8px; font-size: 13px;">';
            html += '<div><strong>' + data.name + '</strong> — Target: ' + fmtNum(targetKg) + ' kg</div>';
            html += '<table class="table" style="margin-top: 4px;"><thead><tr><th>Item</th><th>Berat (kg)</th><th>%</th></tr></thead><tbody>';
            data.items.forEach(function (item) {
                const scaled = targetKg > 0 ? (targetKg * parseFloat(item.percentage) / 100) : 0;
                html += '<tr><td>' + (item.item || '-') + '</td><td>' + fmtNum(scaled) + '</td><td>' + fmtNum(item.percentage) + '%</td></tr>';
            });
            html += '</tbody></table></div>';
            conceptPreview.innerHTML = html;
        }

        foreverCheck.addEventListener('change', function () {
            durationInput.disabled = this.checked;
            if (this.checked) durationInput.value = '';
        });

        function refreshPreview() { showConceptPreview(conceptSelect.value); }

        conceptSelect.addEventListener('change', refreshPreview);
        targetWeightInput.addEventListener('input', refreshPreview);
        targetWeightUnit.addEventListener('change', refreshPreview);
        refreshPreview();
    });
    </script>
    @endpush
</x-layouts.dashboard>
