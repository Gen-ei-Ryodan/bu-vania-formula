<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Production extends Model
{
    protected $fillable = [
        'name',
        'seed_name',
        'concept_id',
        'target_weight_gram',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductionItem::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(ProductionGroup::class);
    }

    public function tabs(): HasMany
    {
        return $this->hasMany(ProductionTab::class);
    }
}
