<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;

    protected $table = 'details_orders';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_unit',
        'subtotal',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_unit' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(OrderService::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '$ ' . number_format($this->price_unit, 2, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return '$ ' . number_format($this->subtotal, 2, ',', '.');
    }

    protected static function boot(){
        
        parent::boot();

        static::creating(function ($detail) {
            $detail->subtotal = $detail->quantity * $detail->price_unit;
            $detail->total = $detail->subtotal;
        });

        static::updating(function ($detail) {
            $detail->subtotal = $detail->quantity * $detail->price_unit;
            $detail->total = $detail->subtotal;
        });
    }
}