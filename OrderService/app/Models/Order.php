<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];
}
