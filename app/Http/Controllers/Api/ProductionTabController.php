<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Services\ProductionTabService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductionTabController extends Controller
{
    public function store(Request $request, Production $production, ProductionTabService $service)
    {
        if (! $production->items()->exists()) {
            throw ValidationException::withMessages([
                'production' => ['Production belum di-generate snapshot.'],
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'input_weight_gram' => ['required', 'integer', 'min:1'],
        ]);

        $tab = $service->createTab(
            $production,
            $validated['name'],
            (int) $validated['input_weight_gram'],
        );

        return response()->json($tab, 201);
    }
}
