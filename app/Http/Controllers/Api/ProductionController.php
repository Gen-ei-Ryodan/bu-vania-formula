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
            'seed_name' => ['required', 'string', 'max:255'],
            'concept_id' => ['required', 'integer', 'exists:concepts,id'],
            'target_weight_kg' => ['required', 'numeric', 'min:0.0001'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
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
