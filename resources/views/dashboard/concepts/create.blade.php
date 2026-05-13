<x-layouts.dashboard title="Buat Concept" heading="Buat Concept">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Concept</h2>
            <a class="btn" href="{{ route('concepts.index') }}">Kembali</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('concepts.store') }}">
                @csrf
                <div class="grid-2">
                    <div class="field">
                        <div class="label">Nama</div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Resep A">
                    </div>
                    <div class="field">
                        <div class="label">Base Weight</div>
                        <div class="inline">
                            <input class="w-220" type="number" step="0.0001" name="base_weight_value" value="{{ old('base_weight_value', 1) }}">
                            <select class="w-160" name="base_weight_unit_id">
                                @php($u = (int) old('base_weight_unit_id', $units->first()?->id ?? 0))
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected($u === $unit->id)>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="muted" style="font-size: 12px;">Disimpan sebagai gram</div>
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
                                <div class="inline">
                                    <div class="field w-280">
                                        <div class="label">Item</div>
                                        <select data-name="item_id">
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field w-220">
                                        <div class="label">Percentage (%)</div>
                                        <input data-name="percentage" type="number" step="0.0001" placeholder="50">
                                    </div>
                                    <div class="field w-220">
                                        <div class="label">Atau Weight</div>
                                        <input data-name="weight_value" type="number" step="0.0001" placeholder="1">
                                    </div>
                                    <div class="field w-160">
                                        <div class="label">Unit</div>
                                        <select data-name="weight_unit_id">
                                            <option value="">-</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="right">
                                        <button class="btn btn-danger" data-repeatable-remove type="button">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Simpan Concept</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
