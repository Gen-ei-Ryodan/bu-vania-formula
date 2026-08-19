<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\RecipePriceService;

class ConceptItem extends Model
{
    protected $fillable = [
        'concept_id',
        'item_id',
        'percentage',
        'weight_kg',
    ];

    protected $casts = [
        'percentage' => 'decimal:4',
        'weight_kg' => 'decimal:4',
    ];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getPriceAttribute(): float
    {
        return app(RecipePriceService::class)->itemCost($this->item, (float) $this->weight_kg);
    }
}
