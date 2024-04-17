<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'from_shop', 'to_shop', 'material_id', 'qty'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'from_shop','to_shop');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
