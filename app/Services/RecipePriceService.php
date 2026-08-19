<?php

namespace App\Services;

use App\Models\Concept;
use App\Models\ConceptItem;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class RecipePriceService
{
    public function itemCost(?Item $item, float $quantity, ?Unit $usageUnit = null): float
    {
        if (! $item || $quantity <= 0 || (float) $item->price <= 0 || (float) $item->price_unit_value <= 0) {
            return 0.0;
        }

        $priceUnit = $item->priceUnit;
        if (! $priceUnit) {
            return 0.0;
        }
        if ($usageUnit && ! $priceUnit->isCompatibleWith($usageUnit)) {
            throw new InvalidArgumentException('Satuan pemakaian dan satuan harga item tidak kompatibel.');
        }

        $pricedQuantity = (float) $item->price_unit_value * (float) $priceUnit->conversion_to_kg;
        $usageQuantity = $usageUnit ? $quantity * (float) $usageUnit->conversion_to_kg : $quantity;

        if ($pricedQuantity <= 0) {
            return 0.0;
        }

        return ((float) $item->price / $pricedQuantity) * $usageQuantity;
    }

    public function breakdown(Concept $concept): Collection
    {
        $concept->loadMissing('items.item.priceUnit', 'items.weightUnit');

        return $concept->items->map(fn (ConceptItem $conceptItem) => [
            'item_id' => $conceptItem->item_id,
            'item_name' => $conceptItem->item?->name,
            'weight_kg' => (float) $conceptItem->weight_kg,
            'weight_unit_id' => $conceptItem->weight_unit_id,
            'weight_unit_name' => $conceptItem->weightUnit?->name,
            'price' => $this->itemCost($conceptItem->item, (float) $conceptItem->weight_kg),
        ]);
    }

    public function total(Collection $conceptItems): float
    {
        $conceptItems->loadMissing('item.priceUnit', 'weightUnit');

        return (float) $conceptItems->sum(
            fn (ConceptItem $conceptItem) => $this->itemCost($conceptItem->item, (float) $conceptItem->weight_kg)
        );
    }
}
