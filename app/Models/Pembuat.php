<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembuat extends Model
{
    protected $fillable = [
        'name',
    ];

    public function concepts(): HasMany
    {
        return $this->hasMany(Concept::class);
    }
}
