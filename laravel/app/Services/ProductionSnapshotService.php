<?php

namespace App\Services;

use App\Models\Production;
use App\Models\ProductionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionSnapshotService
{
    public function generate(Production $production): void
    {
        if ($production->items()->exists()) {
            throw ValidationException::withMessages([
                'production' => ['Snapshot sudah ada untuk production ini.'],
            ]);
        }

        if ($production->tabs()->exists()) {
            throw ValidationException::withMessages([
                'production' => ['Tidak boleh generate snapshot setelah TAB dibuat.'],
            ]);
        }

        $this->buildItems($production);
    }

    public function regenerate(Production $production): void
    {
        $production->items()->delete();

        $this->buildItems($production);
    }

    protected function buildItems(Production $production): void
    {
        $conceptItems = $production->concept->items()->get(['item_id', 'percentage']);

        if ($conceptItems->isEmpty()) {
            throw ValidationException::withMessages([
                'concept_id' => ['Concept belum punya item.'],
            ]);
        }

        $sumPercentage = $conceptItems->sum(fn ($row) => (float) $row->percentage);

        if (abs($sumPercentage - 100.0) > 0.0001) {
            throw ValidationException::withMessages([
                'concept_items' => ['Total persen concept harus 100%.'],
            ]);
        }

        $target = (float) $production->target_weight_kg;

        $candidates = $conceptItems
            ->map(function ($row) use ($target) {
                $raw = $target * ((float) $row->percentage / 100.0);

                return [
                    'item_id' => (int) $row->item_id,
                    'weight_kg' => round($raw, 4),
                ];
            })
            ->values();

        $now = now();

        DB::transaction(function () use ($production, $candidates, $now) {
            $rows = $candidates->map(fn ($row) => [
                'production_id' => $production->id,
                'item_id' => $row['item_id'],
                'weight_kg' => $row['weight_kg'],
                'source' => 'concept',
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            ProductionItem::query()->insert($rows);
        });
    }
}
