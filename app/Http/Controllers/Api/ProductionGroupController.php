<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\ProductionGroup;
use Illuminate\Http\Request;

class ProductionGroupController extends Controller
{
    public function store(Request $request, Production $production)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $group = ProductionGroup::query()->create([
            'production_id' => $production->id,
            'name' => $validated['name'],
        ]);

        return response()->json($group, 201);
    }
}
