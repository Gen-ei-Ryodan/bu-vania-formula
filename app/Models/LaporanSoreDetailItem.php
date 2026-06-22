<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanSoreDetailItem extends Model
{
    protected $table = 'laporan_sore_detail_items';

    protected $fillable = [
        'laporan_sore_detail_id',
        'item_id',
    ];

    public function detail(): BelongsTo
    {
        return $this->belongsTo(LaporanSoreDetail::class, 'laporan_sore_detail_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
