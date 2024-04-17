<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'table_number_id',
        'items',
        'status',
        'checkin',
        'checkout',
        'table_charge',
        'items_charge',
        'total_time',
        'shop_id'
    ];

    protected $casts = [
        'checkin' => 'datetime',
        'checkout' => 'datetime',
        'items' => 'json'
    ];

    public function tableNumber(): BelongsTo
    {
        return $this->belongsTo(TableNumber::class, 'table_number_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
