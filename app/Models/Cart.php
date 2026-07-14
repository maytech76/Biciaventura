<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart'; // Nombre de la tabla

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price',
        'sub_total'
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
