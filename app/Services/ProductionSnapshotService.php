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

        $target = (int) $production->target_weight_gram;

        $candidates = $conceptItems
            ->map(function ($row) use ($target) {
                $raw = $target * ((float) $row->percentage / 100.0);
                $floor = (int) floor($raw);

                return [
                    'item_id' => (int) $row->item_id,
                    'floor' => $floor,
                    'fraction' => $raw - $floor,
                ];
            })
            ->values();

        $allocated = $candidates->sum('floor');
        $remainder = $target - $allocated;

        if ($remainder < 0) {
            throw ValidationException::withMessages([
                'target_weight_gram' => ['Target gram tidak valid untuk komposisi ini.'],
            ]);
        }

        $sorted = $candidates->sortByDesc('fraction')->values();

        for ($i = 0; $i < $remainder; $i++) {
            $index = $i % $sorted->count();
            $sorted[$index]['floor']++;
        }

        $now = now();

        DB::transaction(function () use ($production, $sorted, $now) {
            $rows = $sorted->map(fn ($row) => [
                'production_id' => $production->id,
                'item_id' => $row['item_id'],
                'weight_gram' => $row['floor'],
                'source' => 'concept',
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            ProductionItem::query()->insert($rows);
        });
    }
}
