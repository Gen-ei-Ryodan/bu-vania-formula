<x-layouts.dashboard title="Buat Produksi" heading="Buat Produksi">
    <div class="page-hero">
        <h1>Buat Produksi Baru</h1>
        <p>Buat produksi pakan ternak</p>
    </div>

    @if ($recentProductions->isNotEmpty())
    <div class="content-section">
        <form method="POST" action="{{ route('productions.duplicate') }}">
            @csrf
            <div class="card" style="background: #FFFBEB; border-color: #FDE68A;">
                <div class="card-body" style="padding: 16px 24px;">
                    <div class="field" style="margin: 0;">
                        <div class="label">Salin dari Produksi Sebelumnya</div>
                        <div style="display: flex; gap: 8px;">
                            <select name="source_id" style="flex: 1;" onchange="if(this.value){if(confirm('Salin data dari produksi ini? Semua golongan dan TAB juga akan disalin.')){this.form.submit()}else{this.value=''}}">
                                <option value="">-- Pilih --</option>
                                @foreach ($recentProductions as $rp)
                                    <option value="{{ $rp->id }}">#{{ $rp->id }} - {{ $rp->name }} ({{ $rp->concept?->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif

    <div class="content-section">
        <form method="POST" action="{{ route('productions.store') }}">
            @csrf
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Produksi</h3></div>
                <div class="form-card-body">
                    <div class="form-grid">
                        <div class="field">
                            <div class="label">Nama</div>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Produksi April">
                        </div>
                        <div class="field">
                            <div class="label">Tanggal Campur <span class="label-optional">(opsional)</span></div>
                            <input type="date" name="mix_date" value="{{ old('mix_date') }}">
                        </div>
                        <div class="field">
                            <div class="label">Lokasi</div>
                            <select name="location" id="location-select">
                                <option value="">Pilih Lokasi</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->name }}" @selected(old('location') === $loc->name)>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <div class="label">Kandang</div>
                            <select name="cage" id="cage-select">
                                <option value="">Pilih Kandang</option>
                                @foreach ($locations as $loc)
                                    @foreach ($loc->cages as $cage)
                                        <option value="{{ $cage->name }}" data-location="{{ $loc->name }}" @selected(old('cage') === $cage->name)>{{ $cage->name }} ({{ $loc->name }})</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="field form-grid-full">
                            <div class="label">Konsep (Resep Dasar)</div>
                            <select name="concept_id" id="concept-select">
                                @php $c = (int) old('concept_id', $concepts->first()?->id ?? 0); @endphp
                                @foreach ($concepts as $concept)
                                    <option value="{{ $concept->id }}" @selected($c === $concept->id)>{{ $concept->name }}</option>
                                @endforeach
                            </select>
                            <div id="concept-preview" class="stack" style="margin-top: 8px;"></div>
                        </div>
                        <div class="field">
                            <div class="label">Kapasitas</div>
                            <div class="input-group" style="max-width: 380px;">
                                <input type="number" step="0.0001" name="target_weight_value" value="{{ old('target_weight_value', 1) }}" placeholder="1" id="target-weight-value">
                                <select name="target_weight_unit_id" id="target-weight-unit">
                                    @php
                                        $defaultUnitId = 0;
                                        foreach ($units as $unit) { if ($unit->conversion_to_kg == 1) { $defaultUnitId = $unit->id; break; } }
                                        $u = (int) old('target_weight_unit_id', $defaultUnitId ?: ($units->first()?->id ?? 0));
                                    @endphp
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" data-conv="{{ $unit->conversion_to_kg }}" @selected((isset($u) && $u === $unit->id))>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label">Tanggal Mulai Pakai Konsep</div>
                            <input type="date" name="start_date" value="{{ old('start_date') }}">
                        </div>
                        <div class="field">
                            <div class="label">Durasi (hari)</div>
                            <div class="field-inline">
                                <input type="number" name="duration_days" value="{{ old('duration_days') }}" min="1" placeholder="20" id="duration-days" style="width: 140px;">
                                <label class="field-inline">
                                    <input type="checkbox" name="is_forever" value="1" @checked(old('is_forever')) id="is-forever">
                                    Selamanya
                                </label>
                            </div>
                        </div>
                        <div class="field form-grid-full">
                            <div class="label">Catatan</div>
                            <textarea name="notes">{{ old('notes') }}</textarea>
                        </div>
                        <div class="field">
                            <div class="label">Status</div>
                            <label class="field-inline">
                                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-card-footer">
                    <a class="btn btn-ghost" href="{{ route('productions.index') }}">Batal</a>
                    <button class="btn btn-primary btn-lg" type="submit">Simpan Produksi</button>
                </div>
            </div>
        </form>
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
        const locationSelect = document.getElementById('location-select');
        const cageSelect = document.getElementById('cage-select');
        const concepts = @json($conceptsData ?? []);

        function fmtNum(val) { const n = parseFloat(val); return isNaN(n) ? '0' : n.toFixed(3); }
        function getTargetKg() { return (parseFloat(targetWeightInput.value) || 0) * (parseFloat(targetWeightUnit.options[targetWeightUnit.selectedIndex]?.dataset?.conv) || 1); }

        function showConceptPreview(id) {
            const data = concepts[id]; if (!data) { conceptPreview.innerHTML = ''; return; }
            const targetKg = getTargetKg();
            let html = '<div class="card" style="padding: 8px; font-size: 13px;"><div><strong>' + data.name + '</strong> \u2014 Target: ' + fmtNum(targetKg) + ' kg</div><table class="data" style="margin-top: 4px;"><thead><tr><th>Item</th><th>Berat (kg)</th><th>%</th></tr></thead><tbody>';
            data.items.forEach(function (item) { const scaled = targetKg > 0 ? (targetKg * parseFloat(item.percentage) / 100) : 0; html += '<tr><td>' + (item.item || '-') + '</td><td>' + fmtNum(scaled) + '</td><td>' + fmtNum(item.percentage) + '%</td></tr>'; });
            html += '</tbody></table></div>';
            conceptPreview.innerHTML = html;
        }

        foreverCheck.addEventListener('change', function () { durationInput.disabled = this.checked; if (this.checked) durationInput.value = ''; });
        locationSelect.addEventListener('change', function () {
            Array.from(cageSelect.options).forEach(function (opt) { if (opt.dataset.location) opt.style.display = opt.dataset.location === this.value ? '' : 'none'; }, this);
            cageSelect.value = ''; const firstVisible = cageSelect.querySelector('option[data-location="' + this.value + '"]'); if (firstVisible) cageSelect.value = firstVisible.value;
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
