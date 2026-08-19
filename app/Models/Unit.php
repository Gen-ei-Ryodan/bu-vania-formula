<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'dimension',
        'conversion_to_kg',
    ];

    protected $casts = [
        'conversion_to_kg' => 'decimal:6',
    ];

    public function isCompatibleWith(?self $unit): bool
    {
        return $unit !== null && $this->dimension === $unit->dimension;
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'default_unit_id');
    }

    public function priceItems(): HasMany
    {
        return $this->hasMany(Item::class, 'price_unit_id');
    }
}
