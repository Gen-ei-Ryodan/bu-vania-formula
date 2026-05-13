<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionTab;
use App\Models\ProductionTabItem;
use Illuminate\Http\Request;

class ProductionTabItemController extends Controller
{
    public function store(Request $request, ProductionTab $tab)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'weight_kg' => ['required', 'numeric', 'min:0.0001'],
        ]);

        $tabItem = ProductionTabItem::query()->create([
            'tab_id' => $tab->id,
            'item_id' => (int) $validated['item_id'],
            'weight_kg' => (float) $validated['weight_kg'],
        ]);

        return response()->json($tabItem, 201);
    }
}
