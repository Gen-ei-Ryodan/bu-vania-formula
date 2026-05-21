<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concept extends Model
{
    protected $fillable = [
        'name',
        'base_weight_kg',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ConceptItem::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }
}
