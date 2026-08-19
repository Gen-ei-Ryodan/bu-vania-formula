<?php

namespace App\Services;

use App\Models\ConceptItem;
use App\Models\Concept;
use App\Models\Item;
use Illuminate\Support\Collection;

class RecipePriceService
{
    public function itemCost(?Item $item, float $weightKg): float
    {
        if (! $item || $weightKg <= 0 || (float) $item->price <= 0 || (float) $item->price_unit_value <= 0) {
            return 0.0;
        }

        $priceUnitConversion = (float) ($item->priceUnit?->conversion_to_kg ?? 0);
        $pricedWeightKg = (float) $item->price_unit_value * $priceUnitConversion;

        if ($pricedWeightKg <= 0) {
            return 0.0;
        }

        return ((float) $item->price / $pricedWeightKg) * $weightKg;
    }

    public function breakdown(Concept $concept): Collection
    {
        $concept->loadMissing('items.item.priceUnit');

        return $concept->items->map(fn (ConceptItem $conceptItem) => [
            'item_id' => $conceptItem->item_id,
            'item_name' => $conceptItem->item?->name,
            'weight_kg' => (float) $conceptItem->weight_kg,
            'price' => $this->itemCost($conceptItem->item, (float) $conceptItem->weight_kg),
        ]);
    }

    public function total(Collection $conceptItems): float
    {
        $conceptItems->loadMissing('item.priceUnit');

        return (float) $conceptItems->sum(
            fn (ConceptItem $conceptItem) => $this->itemCost($conceptItem->item, (float) $conceptItem->weight_kg)
        );
    }
}
