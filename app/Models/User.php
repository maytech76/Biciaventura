<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'role', 
        'address',
        'document',
        'city_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'integer',
    ];

    // Relaciones existentes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Scope para obtener solo usuarios customers
     */
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    /**
     * Scope para obtener solo usuarios admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Relación con vehículos (user_id)
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'user_id');
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verificar si es admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar si es customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Verificar si está activo
     */
    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function hasPurchasedProduct($productId)
    {
        return Order::where('user_id', $this->id)
            ->whereHas('orderItems', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'paid')
            ->exists();
    }
}