<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\ConceptItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConceptController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:concepts,name'],
            'base_weight_kg' => ['required', 'numeric', 'min:0.0001'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', 'distinct', 'exists:items,id'],
            'items.*.percentage' => ['required', 'numeric', 'min:0.0001', 'max:100'],
        ]);

        $sumPercentage = collect($validated['items'])->sum(fn ($row) => (float) $row['percentage']);

        if (abs($sumPercentage - 100.0) > 0.0001) {
            throw ValidationException::withMessages([
                'items' => ['Total persen concept harus 100%.'],
            ]);
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
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            ConceptItem::query()->insert($rows);

            return $concept;
        });

        return response()->json($concept->load('items'), 201);
    }
}
