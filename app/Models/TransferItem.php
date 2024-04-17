<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'from_shop', 'to_shop', 'item_id', 'qty'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'from_shop','to_shop');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

}
