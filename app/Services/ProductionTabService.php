<?php

namespace App\Services;

use App\Models\Production;
use App\Models\ProductionTab;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionTabService
{
    public function createTab(Production $production, string $name, int $inputWeightGram): ProductionTab
    {
        if ($inputWeightGram <= 0) {
            throw ValidationException::withMessages([
                'input_weight_gram' => ['Input gram harus lebih besar dari 0.'],
            ]);
        }

        return DB::transaction(function () use ($production, $name, $inputWeightGram) {
            $lockedProduction = Production::query()
                ->whereKey($production->id)
                ->lockForUpdate()
                ->firstOrFail();

            $used = ProductionTab::query()
                ->where('production_id', $lockedProduction->id)
                ->lockForUpdate()
                ->sum('input_weight_gram');

            $available = (int) $lockedProduction->target_weight_gram - (int) $used;

            if ($inputWeightGram > $available) {
                throw ValidationException::withMessages([
                    'input_weight_gram' => ['TAB melebihi sisa gram yang tersedia.'],
                ]);
            }

            $remaining = $available - $inputWeightGram;

            return ProductionTab::query()->create([
                'production_id' => $lockedProduction->id,
                'name' => $name,
                'input_weight_gram' => $inputWeightGram,
                'remaining_weight_gram' => $remaining,
            ]);
        });
    }
}
