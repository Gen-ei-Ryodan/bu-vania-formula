<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Production extends Model
{
    protected $fillable = [
        'name',
        'location',
        'cage',
        'treatment_day',
        'treatment_time',
        'treatment_duration_days',
        'concept_id',
        'target_weight_kg',
        'start_date',
        'duration_days',
        'is_forever',
        'mix_date',
        'notes',
        'production_type',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'mix_date' => 'date',
        'is_forever' => 'boolean',
        'is_active' => 'boolean',
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

    public function scopeBiasa($query)
    {
        return $query->where('production_type', 'biasa');
    }

    public function scopeTreatment($query)
    {
        return $query->where('production_type', 'treatment');
    }
}
