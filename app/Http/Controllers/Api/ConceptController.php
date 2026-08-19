<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\ConceptItem;
use App\Models\Item;
use App\Models\Unit;
use App\Services\RecipePriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConceptController extends Controller
{
    public function store(Request $request, RecipePriceService $priceService)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:concepts,name'],
            'base_weight_kg' => ['required', 'numeric', 'min:0.0001'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', 'distinct', 'exists:items,id'],
            'items.*.percentage' => ['required', 'numeric', 'min:0.0001', 'max:100'],
            'items.*.weight_kg' => ['nullable', 'numeric', 'min:0.000001'],
            'items.*.weight_unit_id' => ['nullable', 'integer', 'exists:units,id'],
        ]);

        $sumPercentage = collect($validated['items'])->sum(fn ($row) => (float) $row['percentage']);

        if (abs($sumPercentage - 100.0) > 0.0001) {
            throw ValidationException::withMessages([
                'items' => ['Total persen concept harus 100%.'],
            ]);
        }

        foreach ($validated['items'] as $row) {
            if (! empty($row['weight_unit_id'])) {
                $priceUnit = Item::query()->with('priceUnit')->findOrFail($row['item_id'])->priceUnit;
                $usageUnit = Unit::query()->findOrFail($row['weight_unit_id']);
                if ($priceUnit && ! $priceUnit->isCompatibleWith($usageUnit)) {
                    throw ValidationException::withMessages([
                        'items' => ['Satuan pemakaian harus kompatibel dengan satuan harga item.'],
                    ]);
                }
            }
        }

        $concept = DB::transaction(function () use ($validated) {
            $concept = Concept::query()->create([
                'name' => $validated['name'],
                'base_weight_kg' => (float) $validated['base_weight_kg'],
            ]);

            $now = now();

            $rows = collect($validated['items'])->map(fn ($row) => [
                'concept_id' => $concept->id,
                'item_id' => (int) $row['item_id'],
                'percentage' => $row['percentage'],
                'weight_kg' => array_key_exists('weight_kg', $row) && $row['weight_kg'] !== null
                    ? $row['weight_kg']
                    : ((float) $validated['base_weight_kg'] * (float) $row['percentage'] / 100),
                'weight_unit_id' => $row['weight_unit_id'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            ConceptItem::query()->insert($rows);

            return $concept;
        });

        $concept->load('items.item.priceUnit');

        return response()->json([
            'concept' => $concept,
            'items' => $priceService->breakdown($concept)->values(),
            'total_price' => $priceService->total($concept->items),
        ], 201);
    }

    public function price(Concept $concept, RecipePriceService $priceService)
    {
        return response()->json([
            'concept_id' => $concept->id,
            'items' => $priceService->breakdown($concept)->values(),
            'total_price' => $priceService->total($concept->items),
        ]);
    }
}
