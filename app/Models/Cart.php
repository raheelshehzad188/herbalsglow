<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'session_id',
        'user_id',
        'product_id',
        'quantity',
        'price',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the product that belongs to this cart item
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Admins\Product::class, 'product_id');
    }

    /**
     * Get the user that owns this cart item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

