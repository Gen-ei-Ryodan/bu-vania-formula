<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanSoreDetail extends Model
{
    protected $table = 'laporan_sore_details';

    protected $fillable = [
        'laporan_sore_id',
        'section',
        'cage_id',
        'nama_tali',
        'konsep_id',
        'jumlah',
        'satuan',
    ];

    public function laporanSore(): BelongsTo
    {
        return $this->belongsTo(LaporanSore::class, 'laporan_sore_id');
    }

    public function cage(): BelongsTo
    {
        return $this->belongsTo(Cage::class);
    }

    public function konsep(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(LaporanSoreDetailItem::class, 'laporan_sore_detail_id');
    }
}
