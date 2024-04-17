<?php

namespace App\Models;

use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use BasicAudit, HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'shop_id',
        'customer_id',
        'items',
        'subtotal',
        'tax',
        'discount',
        'total',
        'payment',
        'charge',
        'refund',
    ];

    protected $casts = [
        'items' => 'json'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'shop_id');
    }
}
