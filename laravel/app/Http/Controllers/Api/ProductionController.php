<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Services\ProductionSnapshotService;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'cage' => ['nullable', 'string', 'max:255'],
                        'treatment_day' => ['nullable', 'integer', 'min:1'],
            'treatment_time' => ['nullable', 'in:pagi,siang,malam,full'],
            'concept_id' => ['required', 'integer', 'exists:concepts,id'],
            'target_weight_kg' => ['required', 'numeric', 'min:0.0001'],
            'start_date' => ['nullable', 'date'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'is_forever' => ['nullable', 'boolean'],
            'mix_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $production = Production::query()->create($validated);

        return response()->json($production, 201);
    }

    public function generate(Production $production, ProductionSnapshotService $service)
    {
        $production->load('concept.items');

        $service->generate($production);

        return response()->json([
            'production_id' => $production->id,
            'status' => 'generated',
        ], 201);
    }
}
