<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionTab extends Model
{
    protected $fillable = [
        'production_id',
        'name',
        'input_weight_kg',
        'remaining_weight_kg',
    ];

    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductionTabItem::class, 'tab_id');
    }
}
