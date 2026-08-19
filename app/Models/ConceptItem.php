<?php

namespace App\Models;

use App\Services\RecipePriceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptItem extends Model
{
    protected $fillable = [
        'concept_id',
        'item_id',
        'percentage',
        'weight_kg',
        'weight_unit_id',
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

    public function weightUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'weight_unit_id');
    }

    public function getPriceAttribute(): float
    {
        // weight_kg is already normalized; weightUnit remains the input unit for auditability.
        return app(RecipePriceService::class)->itemCost($this->item, (float) $this->weight_kg);
    }
}
