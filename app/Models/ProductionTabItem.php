<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionTabItem extends Model
{
    protected $fillable = [
        'tab_id',
        'item_id',
        'weight_kg',
    ];

    public function tab(): BelongsTo
    {
        return $this->belongsTo(ProductionTab::class, 'tab_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
