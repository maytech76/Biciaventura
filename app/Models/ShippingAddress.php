<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'address', 
        'zone_id', // Ahora relacionamos directamente con la zona
    ];

    /**
     * Relación: Una dirección de envío pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una dirección de envío pertenece a una zona
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Relación: Acceder a la ciudad a través de la zona
     */
    public function city()
    {
        return $this->hasOneThrough(City::class, Zone::class, 'id', 'id', 'zone_id', 'city_id');
    }

    /**
     * Accesor para obtener el precio de envío
     */
    public function getShippingPriceAttribute()
    {
        return $this->zone->price ?? 0;
    }

    /**
     * Accesor para obtener el nombre de la ciudad
     */
    public function getCityNameAttribute()
    {
        return $this->zone->city->name ?? '';
    }

    /**
     * Accesor para obtener el nombre de la zona
     */
    public function getZoneNameAttribute()
    {
        return $this->zone->name ?? '';
    }

    /**
     * Accesor para obtener información completa de ubicación
     */
    public function getFullLocationAttribute()
    {
        return $this->zone->city->name . ' - ' . $this->zone->name . ' - ' . $this->address;
    }
}