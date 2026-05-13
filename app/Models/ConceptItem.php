<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptItem extends Model
{
    protected $fillable = [
        'concept_id',
        'item_id',
        'percentage',
        'weight_gram',
    ];

    protected $casts = [
        'percentage' => 'decimal:4',
        'weight_gram' => 'integer',
    ];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
