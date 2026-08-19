<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\RecipePriceService;

class Concept extends Model
{
    protected $fillable = [
        'name',
        'pembuat_id',
        'base_weight_kg',
        'start_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ConceptItem::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(Pembuat::class);
    }

    public function getTotalPriceAttribute(): float
    {
        return app(RecipePriceService::class)->total($this->items);
    }
}
