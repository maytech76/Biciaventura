<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory; // SoftDeletes es opcional pero recomendado

    /**
     * The table associated with the model.
     */
    protected $table = 'customers';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'document',
        'full_name',
        'celular',
        'email',
        'city_id',
        'status',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // No hay campos sensibles por ahora
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the city that owns the customer.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get the orders for the customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the full name with document for display.
     */
    public function getFullNameWithDocumentAttribute(): string
    {
        return "{$this->full_name} - {$this->document}";
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to search by document or name.
     */
    public function scopeSearch($query, $search){

        if ($search) {
            return $query->where('document', 'LIKE', "%{$search}%")
                        ->orWhere('full_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
        }
        return $query;
    }

    /**
     * Scope a query to filter by city.
     */
    public function scopeByCity($query, $cityId)
    {
        if ($cityId) {
            return $query->where('city_id', $cityId);
        }
        return $query;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Evento antes de crear
        static::creating(function ($customer) {
            // Validar formato de email
            if (!filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Email inválido');
            }
        });

        // Evento después de crear
        static::created(function ($customer) {
            // Puedes agregar lógica adicional aquí
            // Ejemplo: Enviar email de bienvenida
        });
    }

   

    /**
     * Set the customer's email in lowercase.
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Set the customer's document.
     */
    public function setDocumentAttribute($value): void
    {
        $this->attributes['document'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Check if customer has orders.
     */
    public function hasOrders(): bool
    {
        return $this->orders()->count() > 0;
    }

    /**
     * Get the customer's total orders count.
     */
    public function getOrdersCountAttribute(): int
    {
        return $this->orders()->count();
    }
}