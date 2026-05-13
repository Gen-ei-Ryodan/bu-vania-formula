<?php

namespace App\Services;

use App\Models\Production;
use App\Models\ProductionTab;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionTabService
{
    public function createTab(Production $production, string $name, float $inputWeightKg): ProductionTab
    {
        if ($inputWeightKg <= 0) {
            throw ValidationException::withMessages([
                'input_weight_kg' => ['Input kg harus lebih besar dari 0.'],
            ]);
        }

        return DB::transaction(function () use ($production, $name, $inputWeightKg) {
            $lockedProduction = Production::query()
                ->whereKey($production->id)
                ->lockForUpdate()
                ->firstOrFail();

            $used = ProductionTab::query()
                ->where('production_id', $lockedProduction->id)
                ->lockForUpdate()
                ->sum('input_weight_kg');

            $available = (float) $lockedProduction->target_weight_kg - (float) $used;

            if ($inputWeightKg > $available) {
                throw ValidationException::withMessages([
                    'input_weight_kg' => ['TAB melebihi sisa kg yang tersedia.'],
                ]);
            }

            $remaining = $available - $inputWeightKg;

            return ProductionTab::query()->create([
                'production_id' => $lockedProduction->id,
                'name' => $name,
                'input_weight_kg' => $inputWeightKg,
                'remaining_weight_kg' => $remaining,
            ]);
        });
    }
}
