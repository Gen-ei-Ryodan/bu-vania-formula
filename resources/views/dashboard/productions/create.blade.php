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
                        <div class="label">Nama Bibit</div>
                        <input type="text" name="seed_name" value="{{ old('seed_name') }}" placeholder="Bibit A">
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
                        <div class="muted" style="font-size: 12px;">Disimpan sebagai gram</div>
                    </div>
                    <div class="field">
                        <div class="label">Start Date</div>
                        <input type="date" name="start_date" value="{{ old('start_date') }}">
                    </div>
                    <div class="field">
                        <div class="label">End Date</div>
                        <input type="date" name="end_date" value="{{ old('end_date') }}">
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <div class="label">Notes</div>
                        <textarea name="notes">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Simpan Production</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
