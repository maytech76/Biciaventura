<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'city_id',
    ];

    /**
     * Relación: Una zona pertenece a una ciudad
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relación: Una zona tiene muchas direcciones de envío
     */
    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    /**
     * Scope para buscar zonas por ciudad
     */
    public function scopeByCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * Scope para ordenar por precio
     */
    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }
}