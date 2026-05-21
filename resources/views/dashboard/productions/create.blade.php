<x-layouts.dashboard title="Buat Produksi" heading="Buat Produksi">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Produksi</h2>
            <a class="btn" href="{{ route('productions.index') }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('productions.store') }}">
                @csrf
                <div class="grid-2">
                    <div class="field">
                        <div class="label">Nama</div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Produksi April">
                    </div>
                    <div class="field">
                        <div class="label">Tgl Mulai</div>
                        <input type="date" name="start_date" value="{{ old('start_date') }}">
                    </div>
                    <div class="field">
                        <div class="label">Lokasi</div>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Lokasi A">
                    </div>
                    <div class="field">
                        <div class="label">Kandang</div>
                        <input type="text" name="cage" value="{{ old('cage') }}" placeholder="Kandang 1">
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <div class="label">Konsep (Resep Dasar)</div>
                        <select name="concept_id" id="concept-select">
                            @php
                                $c = (int) old('concept_id', $concepts->first()?->id ?? 0);
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
                            <input type="number" step="0.0001" name="target_weight_value" value="{{ old('target_weight_value', 1) }}" placeholder="1">
                            <select name="target_weight_unit_id">
                                @php
                                    $defaultUnitId = 0;
                                    foreach ($units as $unit) {
                                        if ($unit->conversion_to_kg == 1) { $defaultUnitId = $unit->id; break; }
                                    }
                                    $u = (int) old('target_weight_unit_id', $defaultUnitId ?: ($units->first()?->id ?? 0));
                                @endphp
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected((isset($u) && $u === $unit->id))>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                    <div class="field">
                        <div class="label">Tanggal Campur</div>
                        <input type="date" name="mix_date" value="{{ old('mix_date') }}">
                    </div>
                    <div class="field">
                        <div class="label">Durasi (hari)</div>
                        <div class="inline">
                            <input type="number" name="duration_days" value="{{ old('duration_days') }}" min="1" placeholder="20" id="duration-days" style="width: 140px;">
                            <label class="inline" style="align-items: center;">
                                <input type="checkbox" name="is_forever" value="1" @checked(old('is_forever')) id="is-forever">
                                Selamanya
                            </label>
                        </div>
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <div class="label">Catatan</div>
                        <textarea name="notes">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Simpan Produksi</button>
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

        const concepts = @json($conceptsData ?? []);

        function fmtNum(val) {
            const n = parseFloat(val);
            if (isNaN(n)) return '0';
            return n % 1 === 0 ? n.toFixed(0) : n.toFixed(2);
        }

        function showConceptPreview(id) {
            const data = concepts[id];
            if (!data) { conceptPreview.innerHTML = ''; return; }
            let html = '<div class="card" style="padding: 8px; font-size: 13px;">';
            html += '<div><strong>' + data.name + '</strong> — Base: ' + fmtNum(data.base_weight_kg) + ' kg</div>';
            html += '<table class="table" style="margin-top: 4px;"><thead><tr><th>Item</th><th>Berat (kg)</th><th>%</th></tr></thead><tbody>';
            data.items.forEach(function (item) {
                html += '<tr><td>' + (item.item || '-') + '</td><td>' + fmtNum(item.weight_kg) + '</td><td>' + fmtNum(item.percentage) + '%</td></tr>';
            });
            html += '</tbody></table></div>';
            conceptPreview.innerHTML = html;
        }

        foreverCheck.addEventListener('change', function () {
            durationInput.disabled = this.checked;
            if (this.checked) durationInput.value = '';
        });

        conceptSelect.addEventListener('change', function () { showConceptPreview(this.value); });
        showConceptPreview(conceptSelect.value);
    });
    </script>
    @endpush
</x-layouts.dashboard>