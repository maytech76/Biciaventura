<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [

        'name',
        'state_id',
        'status'
    ];

    /**
     * Relación: Una ciudad tiene muchas zonas
     */
    public function zones(){

        return $this->hasMany(Zone::class);
    }



    /**
     * Relación: Una ciudad tiene muchas direcciones de envío a través de zonas
     */
    public function shippingAddresses()
    {
        return $this->hasManyThrough(ShippingAddress::class, Zone::class);
    }

    /**
     * Accesor para obtener zonas ordenadas por precio
     */
    public function getZonesOrderedByPriceAttribute()
    {
        return $this->zones()->orderBy('price')->get();
    }

     /* Ciudad pertenece a un estado */
    public function state(){
        return $this->belongsTo(State::class);
    }

    /**
     * Get the customers for the city.
     */
    public function customers(){
        return $this->hasMany(Customer::class);
    }
}