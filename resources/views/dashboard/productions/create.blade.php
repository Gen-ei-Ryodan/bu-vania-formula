<x-layouts.dashboard title="Buat Production" heading="Buat Production">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Production</h2>
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
                        <div class="label">Lokasi</div>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Lokasi A">
                    </div>
                    <div class="field">
                        <div class="label">Kandang</div>
                        <input type="text" name="cage" value="{{ old('cage') }}" placeholder="Kandang 1">
                    </div>
                    <div class="field">
                        <div class="label">Jenis Produksi</div>
                        <select name="production_type" id="production-type">
                            <option value="biasa" @selected(old('production_type', 'biasa') === 'biasa')>Biasa</option>
                            <option value="pengobatan" @selected(old('production_type') === 'pengobatan')>Pengobatan</option>
                        </select>
                    </div>
                    <div class="field treatment-field" style="display: none;">
                        <div class="label">Hari Pengobatan ke-</div>
                        <input type="number" name="treatment_day" value="{{ old('treatment_day') }}" min="1" placeholder="1">
                    </div>
                    <div class="field treatment-field" style="display: none;">
                        <div class="label">Waktu Pengobatan</div>
                        <select name="treatment_time">
                            <option value="">Pilih</option>
                            <option value="pagi" @selected(old('treatment_time') === 'pagi')>Pagi</option>
                            <option value="siang" @selected(old('treatment_time') === 'siang')>Siang</option>
                            <option value="malam" @selected(old('treatment_time') === 'malam')>Malam</option>
                            <option value="full" @selected(old('treatment_time') === 'full')>Full</option>
                        </select>
                    </div>
                    <div class="field">
                        <div class="label">Concept</div>
                        <select name="concept_id">
                            @php($c = (int) old('concept_id', $concepts->first()?->id ?? 0))
                            @foreach ($concepts as $concept)
                                <option value="{{ $concept->id }}" @selected($c === $concept->id)>{{ $concept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <div class="label">Target Weight</div>
                        <div class="inline">
                            <input class="w-220" type="number" step="0.0001" name="target_weight_value" value="{{ old('target_weight_value', 1) }}">
                            <select class="w-160" name="target_weight_unit_id">
                                @php($u = (int) old('target_weight_unit_id', $units->first()?->id ?? 0))
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected($u === $unit->id)>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="muted" style="font-size: 12px;">Disimpan sebagai kg</div>
                    </div>
                    <div class="field">
                        <div class="label">Start Date</div>
                        <input type="date" name="start_date" value="{{ old('start_date') }}">
                    </div>
                    <div class="field">
                        <div class="label">Tanggal Campur</div>
                        <input type="date" name="mix_date" value="{{ old('mix_date') }}">
                    </div>
                    <div class="field">
                        <div class="label">Durasi (hari)</div>
                        <div class="inline">
                            <input class="w-220" type="number" name="duration_days" value="{{ old('duration_days') }}" min="1" placeholder="20" id="duration-days">
                            <label class="inline" style="margin-left: 8px; align-items: center;">
                                <input type="checkbox" name="is_forever" value="1" @checked(old('is_forever')) id="is-forever">
                                Selamanya
                            </label>
                        </div>
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <div class="label">Notes</div>
                        <textarea name="notes">{{ old('notes') }}</textarea>
                    </div>
                </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('production-type');
    const treatmentFields = document.querySelectorAll('.treatment-field');
    const durationInput = document.getElementById('duration-days');
    const foreverCheck = document.getElementById('is-forever');

    typeSelect.addEventListener('change', function () {
        const show = this.value === 'pengobatan';
        treatmentFields.forEach(function (el) { el.style.display = show ? '' : 'none'; });
    });

    typeSelect.dispatchEvent(new Event('change'));

    foreverCheck.addEventListener('change', function () {
        durationInput.disabled = this.checked;
        if (this.checked) durationInput.value = '';
    });
});
</script>

                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Simpan Production</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
