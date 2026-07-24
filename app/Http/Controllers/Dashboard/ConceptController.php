<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\ConceptItem;
use App\Models\Item;
use App\Models\Pembuat;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConceptController extends Controller
{
    public function index(Request $request)
    {
        $query = Concept::query()->with('pembuat')->orderByDesc('id');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        return view('dashboard.concepts.index', [
            'concepts' => $query->get(),
        ]);
    }

    public function create()
    {
        $unitsMap = Unit::query()->pluck('conversion_to_kg', 'id')->map(fn ($v) => (float) $v)->toArray();

        $allConcepts = Concept::query()->with('items.item')->orderBy('name')->get()->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'base_weight_kg' => (float) $c->base_weight_kg,
            'items' => $c->items->map(fn ($i) => [
                'item_id' => $i->item_id,
                'item_name' => $i->item?->name ?? '?',
                'weight_kg' => (float) $i->weight_kg,
            ])->values(),
        ])->values();

        return view('dashboard.concepts.create', [
            'items' => Item::query()->orderBy('name')->get(),
            'units' => Unit::query()->orderBy('name')->get(),
            'pembuats' => Pembuat::query()->orderBy('name')->get(),
            'unitsData' => $unitsMap,
            'allConcepts' => $allConcepts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:concepts,name'],
            'pembuat_id' => ['nullable', 'integer', 'exists:pembuats,id'],
            'start_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'base_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'base_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', 'distinct', 'exists:items,id'],
            'items.*.percentage' => ['nullable', 'numeric', 'min:0.0001', 'max:100'],
            'items.*.weight_value' => ['nullable', 'numeric', 'min:0.0001'],
            'items.*.weight_unit_id' => ['nullable', 'integer', 'exists:units,id'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['base_weight_unit_id']);
        $baseWeightKg = (float) $validated['base_weight_value'] * (float) $unit->conversion_to_kg;

        $items = collect($validated['items'])->map(function (array $row) use ($baseWeightKg) {
            $hasPercentage = array_key_exists('percentage', $row) && $row['percentage'] !== null && $row['percentage'] !== '';
            $hasWeight = array_key_exists('weight_value', $row) && $row['weight_value'] !== null && $row['weight_value'] !== '';

            if ($hasWeight) {
                $unitId = (int) ($row['weight_unit_id'] ?? 0);
                if ($unitId <= 0) {
                    throw ValidationException::withMessages([
                        'items' => ['Jika isi weight, unit wajib dipilih.'],
                    ]);
                }
                $unit = Unit::query()->findOrFail($unitId);
                $weightKg = (float) $row['weight_value'] * (float) $unit->conversion_to_kg;
                $percentage = $baseWeightKg > 0 ? (($weightKg / $baseWeightKg) * 100.0) : 0.0;
            } elseif ($hasPercentage) {
                $percentage = (float) $row['percentage'];
                $weightKg = ($baseWeightKg * $percentage) / 100.0;
            } else {
                throw ValidationException::withMessages([
                    'items' => ['Per item wajib isi weight + unit atau percentage.'],
                ]);
            }

            return [
                'item_id' => (int) $row['item_id'],
                'percentage' => $percentage,
                'weight_kg' => $weightKg,
            ];
        })->values();

        $sumPercentage = $items->sum(fn ($row) => (float) $row['percentage']);
        if (abs($sumPercentage - 100.0) > 0.01) {
            throw ValidationException::withMessages([
                'items' => ['Total persen concept harus 100%.'],
            ]);
        }

        $allocatedWeight = (float) $items->sum('weight_kg');
        $remainder = $baseWeightKg - $allocatedWeight;
        if ($remainder < 0) {
            throw ValidationException::withMessages([
                'items' => ['Total weight melebihi base weight.'],
            ]);
        }

        if ($remainder > 0 && $items->isNotEmpty()) {
            $items = $items->map(function (array $row, int $index) use ($remainder) {
                if ($index === 0) {
                    $row['weight_kg'] = (float) $row['weight_kg'] + $remainder;
                }

                return $row;
            });
        }

        $concept = DB::transaction(function () use ($validated, $baseWeightKg, $items) {
            $concept = Concept::query()->create([
                'name' => $validated['name'],
                'pembuat_id' => $validated['pembuat_id'] ?? null,
                'base_weight_kg' => $baseWeightKg,
                'start_date' => $validated['start_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $now = now();
            $rows = $items->map(fn ($row) => [
                'concept_id' => $concept->id,
                'item_id' => (int) $row['item_id'],
                'percentage' => $row['percentage'],
                'weight_kg' => $row['weight_kg'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            ConceptItem::query()->insert($rows);

            return $concept;
        });

        return redirect()->route('concepts.show', $concept)->with('ok', 'Konsep dibuat.');
    }

    public function show(Concept $concept)
    {
        $concept->load(['items.item', 'pembuat']);

        return view('dashboard.concepts.show', [
            'concept' => $concept,
        ]);
    }

    public function destroy(Concept $concept)
    {
        if ($concept->productions()->exists()) {
            return redirect()->route('concepts.index')->with('error', 'Konsep tidak bisa dihapus karena masih digunakan di produksi.');
        }

        $concept->delete();

        return redirect()->route('concepts.index')->with('ok', 'Konsep dihapus.');
    }

    public function edit(Concept $concept)
    {
        $concept->load(['items.item']);
        $unitsMap = Unit::query()->pluck('conversion_to_kg', 'id')->map(fn ($v) => (float) $v)->toArray();

        return view('dashboard.concepts.edit', [
            'concept' => $concept,
            'items' => Item::query()->orderBy('name')->get(),
            'units' => Unit::query()->orderBy('name')->get(),
            'pembuats' => Pembuat::query()->orderBy('name')->get(),
            'unitsData' => $unitsMap,
        ]);
    }

    public function update(Request $request, Concept $concept)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:concepts,name,'.$concept->id],
            'pembuat_id' => ['nullable', 'integer', 'exists:pembuats,id'],
            'start_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'base_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'base_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', 'distinct', 'exists:items,id'],
            'items.*.percentage' => ['nullable', 'numeric', 'min:0.0001', 'max:100'],
            'items.*.weight_value' => ['nullable', 'numeric', 'min:0.0001'],
            'items.*.weight_unit_id' => ['nullable', 'integer', 'exists:units,id'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['base_weight_unit_id']);
        $baseWeightKg = (float) $validated['base_weight_value'] * (float) $unit->conversion_to_kg;

        $items = collect($validated['items'])->map(function (array $row) use ($baseWeightKg) {
            $hasPercentage = array_key_exists('percentage', $row) && $row['percentage'] !== null && $row['percentage'] !== '';
            $hasWeight = array_key_exists('weight_value', $row) && $row['weight_value'] !== null && $row['weight_value'] !== '';

            if ($hasWeight) {
                $unitId = (int) ($row['weight_unit_id'] ?? 0);
                if ($unitId <= 0) {
                    throw ValidationException::withMessages([
                        'items' => ['Jika isi weight, unit wajib dipilih.'],
                    ]);
                }
                $unit = Unit::query()->findOrFail($unitId);
                $weightKg = (float) $row['weight_value'] * (float) $unit->conversion_to_kg;
                $percentage = $baseWeightKg > 0 ? (($weightKg / $baseWeightKg) * 100.0) : 0.0;
            } elseif ($hasPercentage) {
                $percentage = (float) $row['percentage'];
                $weightKg = ($baseWeightKg * $percentage) / 100.0;
            } else {
                throw ValidationException::withMessages([
                    'items' => ['Per item wajib isi weight + unit atau percentage.'],
                ]);
            }

            return [
                'item_id' => (int) $row['item_id'],
                'percentage' => $percentage,
                'weight_kg' => $weightKg,
            ];
        })->values();

        $sumPercentage = $items->sum(fn ($row) => (float) $row['percentage']);
        if (abs($sumPercentage - 100.0) > 0.01) {
            throw ValidationException::withMessages([
                'items' => ['Total persen concept harus 100%.'],
            ]);
        }

        $allocatedWeight = (float) $items->sum('weight_kg');
        $remainder = $baseWeightKg - $allocatedWeight;
        if ($remainder < 0) {
            throw ValidationException::withMessages([
                'items' => ['Total weight melebihi base weight.'],
            ]);
        }

        if ($remainder > 0 && $items->isNotEmpty()) {
            $items = $items->map(function (array $row, int $index) use ($remainder) {
                if ($index === 0) {
                    $row['weight_kg'] = (float) $row['weight_kg'] + $remainder;
                }

                return $row;
            });
        }

        DB::transaction(function () use ($concept, $validated, $baseWeightKg, $items) {
            $concept->update([
                'name' => $validated['name'],
                'pembuat_id' => $validated['pembuat_id'] ?? null,
                'base_weight_kg' => $baseWeightKg,
                'start_date' => $validated['start_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $concept->items()->delete();

            $now = now();
            $rows = $items->map(fn ($row) => [
                'concept_id' => $concept->id,
                'item_id' => (int) $row['item_id'],
                'percentage' => $row['percentage'],
                'weight_kg' => $row['weight_kg'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            ConceptItem::query()->insert($rows);
        });

        return redirect()->route('concepts.show', $concept)->with('ok', 'Konsep diupdate.');
    }
}
