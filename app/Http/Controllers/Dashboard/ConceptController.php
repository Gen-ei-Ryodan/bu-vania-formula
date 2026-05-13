<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\ConceptItem;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConceptController extends Controller
{
    public function index()
    {
        return view('dashboard.concepts.index', [
            'concepts' => Concept::query()->orderByDesc('id')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.concepts.create', [
            'items' => Item::query()->orderBy('name')->get(),
            'units' => Unit::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:concepts,name'],
            'base_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'base_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', 'distinct', 'exists:items,id'],
            'items.*.percentage' => ['nullable', 'numeric', 'min:0.0001', 'max:100'],
            'items.*.weight_value' => ['nullable', 'numeric', 'min:0.0001'],
            'items.*.weight_unit_id' => ['nullable', 'integer', 'exists:units,id'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['base_weight_unit_id']);
        $baseWeightGram = (int) round(((float) $validated['base_weight_value']) * (int) $unit->conversion_to_gram);

        $items = collect($validated['items'])->map(function (array $row) use ($baseWeightGram) {
            $hasPercentage = array_key_exists('percentage', $row) && $row['percentage'] !== null && $row['percentage'] !== '';
            $hasWeight = array_key_exists('weight_value', $row) && $row['weight_value'] !== null && $row['weight_value'] !== '';

            if ($hasPercentage && $hasWeight) {
                throw ValidationException::withMessages([
                    'items' => ['Per item hanya boleh isi salah satu: percentage atau weight + unit.'],
                ]);
            }

            if (! $hasPercentage && ! $hasWeight) {
                throw ValidationException::withMessages([
                    'items' => ['Per item wajib isi percentage atau weight + unit.'],
                ]);
            }

            $weightGram = null;
            $percentage = null;

            if ($hasPercentage) {
                $percentage = (float) $row['percentage'];
                $weightGram = (int) floor(($baseWeightGram * $percentage) / 100.0);
            } else {
                $unitId = (int) ($row['weight_unit_id'] ?? 0);
                if ($unitId <= 0) {
                    throw ValidationException::withMessages([
                        'items' => ['Jika isi weight, unit wajib dipilih.'],
                    ]);
                }

                $unit = Unit::query()->findOrFail($unitId);
                $weightGram = (int) round(((float) $row['weight_value']) * (int) $unit->conversion_to_gram);
                $percentage = $baseWeightGram > 0 ? (($weightGram / $baseWeightGram) * 100.0) : 0.0;
            }

            return [
                'item_id' => (int) $row['item_id'],
                'percentage' => $percentage,
                'weight_gram' => $weightGram,
            ];
        })->values();

        $sumPercentage = $items->sum(fn ($row) => (float) $row['percentage']);
        if (abs($sumPercentage - 100.0) > 0.01) {
            throw ValidationException::withMessages([
                'items' => ['Total persen concept harus 100%.'],
            ]);
        }

        $allocatedWeight = (int) $items->sum('weight_gram');
        $remainder = $baseWeightGram - $allocatedWeight;
        if ($remainder < 0) {
            throw ValidationException::withMessages([
                'items' => ['Total weight melebihi base weight.'],
            ]);
        }

        if ($remainder > 0 && $items->isNotEmpty()) {
            $items = $items->map(function (array $row, int $index) use ($remainder) {
                if ($index === 0) {
                    $row['weight_gram'] = (int) $row['weight_gram'] + $remainder;
                }

                return $row;
            });
        }

        $concept = DB::transaction(function () use ($validated, $baseWeightGram, $items) {
            $concept = Concept::query()->create([
                'name' => $validated['name'],
                'base_weight_gram' => $baseWeightGram,
            ]);

            $now = now();
            $rows = $items->map(fn ($row) => [
                'concept_id' => $concept->id,
                'item_id' => (int) $row['item_id'],
                'percentage' => $row['percentage'],
                'weight_gram' => $row['weight_gram'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            ConceptItem::query()->insert($rows);

            return $concept;
        });

        return redirect()->route('concepts.show', $concept)->with('ok', 'Concept dibuat.');
    }

    public function show(Concept $concept)
    {
        $concept->load(['items.item']);

        return view('dashboard.concepts.show', [
            'concept' => $concept,
        ]);
    }
}
