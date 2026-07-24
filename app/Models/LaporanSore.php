<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanSore extends Model
{
    protected $table = 'laporan_sore';

    protected $fillable = [
        'location_id',
        'tanggal',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(LaporanSoreDetail::class, 'laporan_sore_id');
    }
}
