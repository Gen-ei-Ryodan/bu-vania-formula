<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionGroup extends Model
{
    protected $fillable = [
        'production_id',
        'name',
    ];

    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductionGroupItem::class, 'group_id');
    }
}
