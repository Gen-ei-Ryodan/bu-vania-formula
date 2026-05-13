<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionGroupItem extends Model
{
    protected $fillable = [
        'group_id',
        'item_id',
        'weight_kg',
        'is_dosis',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductionGroup::class, 'group_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
