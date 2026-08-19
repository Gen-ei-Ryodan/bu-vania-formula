<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'default_unit_id',
        'price',
        'price_unit_value',
        'price_unit_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_unit_value' => 'decimal:6',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function defaultUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'default_unit_id');
    }

    public function priceUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'price_unit_id');
    }

    public function conceptItems(): HasMany
    {
        return $this->hasMany(ConceptItem::class);
    }

    public function productionItems(): HasMany
    {
        return $this->hasMany(ProductionItem::class);
    }

    public function productionGroupItems(): HasMany
    {
        return $this->hasMany(ProductionGroupItem::class);
    }

    public function productionTabItems(): HasMany
    {
        return $this->hasMany(ProductionTabItem::class);
    }
}
