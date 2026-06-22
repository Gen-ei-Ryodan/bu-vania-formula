<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionGroup;
use App\Models\ProductionGroupItem;
use Illuminate\Http\Request;

class ProductionGroupItemController extends Controller
{
    public function store(Request $request, ProductionGroup $group)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'weight_kg' => ['required', 'numeric', 'min:0.0001'],
        ]);

        $groupItem = ProductionGroupItem::query()->create([
            'group_id' => $group->id,
            'item_id' => (int) $validated['item_id'],
            'weight_kg' => (float) $validated['weight_kg'],
        ]);

        return response()->json($groupItem, 201);
    }
}
