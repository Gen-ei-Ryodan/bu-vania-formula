<x-layouts.dashboard title="Edit Concept" heading="Edit Concept">
    <div class="panel">
        <div class="panel-header">
            <h2>Form Concept</h2>
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
                        <div class="label">Base Weight</div>
                        <div class="inline">
                            <input class="w-220" type="number" step="0.0001" name="base_weight_value" id="base-weight-value" value="{{ old('base_weight_value', $concept->base_weight_kg) }}">
                            <select class="w-160" name="base_weight_unit_id" id="base-weight-unit">
                                @php($u = (int) old('base_weight_unit_id', 0))
                                @foreach ($units as $unit)
                                    @if ($unit->conversion_to_kg == 1)
                                        @php($u = $u ?: $unit->id)
                                    @endif
                                    <option value="{{ $unit->id }}" @selected($u === $unit->id)>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="muted" style="font-size: 12px;">Disimpan sebagai kg</div>
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
                                        <input data-name="percentage" type="number" step="0.0001" placeholder="50" readonly>
                                    </div>
                                    <div class="field w-220">
                                        <div class="label">Weight (kg)</div>
                                        <input data-name="weight_value" type="number" step="0.0001" placeholder="1" data-calc-percentage>
                                    </div>
                                    <div class="field w-160">
                                        <div class="label">Unit</div>
                                        <select data-name="weight_unit_id">
                                            <option value="">-</option>
                                            @foreach ($units as $unit)
                                                @if ($unit->conversion_to_kg == 1)
                                                    <option value="{{ $unit->id }}" selected>{{ $unit->name }}</option>
                                                @else
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endif
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
                    <button class="btn btn-primary" type="submit">Update Concept</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const baseWeightInput = document.getElementById('base-weight-value');

    function calcPercentage(row) {
        const weightInput = row.querySelector('[data-calc-percentage]');
        const pctInput = row.querySelector('[data-name="percentage"]');
        const baseWeight = parseFloat(baseWeightInput.value) || 0;

        weightInput.addEventListener('input', function () {
            const weight = parseFloat(this.value) || 0;
            if (baseWeight > 0 && weight > 0) {
                pctInput.value = ((weight / baseWeight) * 100).toFixed(4);
            } else {
                pctInput.value = '';
            }
        });
    }

    const observer = new MutationObserver(function () {
        document.querySelectorAll('[data-repeatable-row]').forEach(function (row) {
            if (!row.dataset.listenerAttached) {
                row.dataset.listenerAttached = '1';
                calcPercentage(row);
            }
        });
    });

    observer.observe(document.querySelector('[data-repeatable-list]'), { childList: true, subtree: false });

    // Prefill existing items
    const existingItems = @json($concept->items);
    const listContainer = document.querySelector('[data-repeatable-list]');
    const template = document.querySelector('[data-repeatable] template');

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
        unitSelect.value = ''; // since stored as kg, we'll leave unit as -

        clone.dataset.listenerAttached = '1';
        calcPercentage(clone);
    });
});
</script>
@endpush
