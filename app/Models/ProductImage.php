<?php
// app/Models/ProductImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'path',
        'is_primary',
        'order'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    // Relación con producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor para URL completa
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    // Scope para imágenes primarias
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Scope ordenado
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}