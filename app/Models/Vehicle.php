<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'brand_id',
        'model',
        'user_id',  // ← Cambiado a user_id
        'status',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Los tipos de vehículos disponibles.
     *
     * @var array<string>
     */
    public const TYPES = ['Mtb', 'Ruta', 'Enduro', 'Bmx', 'Niños', 'E-Bike'];

    /**
     * Obtener el usuario (cliente) propietario del vehículo.
     */
    public function user(){

        return $this->belongsTo(User::class);
    }

    /**
     * Obtener la marca del vehículo.
     */
    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    /**
     * Scope para filtrar vehículos activos.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope para filtrar por tipo de vehículo.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para filtrar por usuario.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Verificar si el vehículo está activo.
     */
    public function isActive(): bool
    {
        return $this->status;
    }

    /**
     * Activar el vehículo.
     */
    public function activate(): void
    {
        $this->update(['status' => true]);
    }

    /**
     * Desactivar el vehículo.
     */
    public function deactivate(): void
    {
        $this->update(['status' => false]);
    }

    /**
     * Obtener el nombre del cliente (accesor).
     */
    public function getCustomerNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'Sin cliente';
    }
}