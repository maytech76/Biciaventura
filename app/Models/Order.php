<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'user_address_id', 
        'total_price', 
        'status'];


    protected $casts = [
        'total_price' => 'decimal:2',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAddress()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'user_address_id');
    }


}
