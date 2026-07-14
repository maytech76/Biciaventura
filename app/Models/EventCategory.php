<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'max_age',
        'min_age',
        'gender_restriction',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'min_age' => 'integer',
        'max_age' => 'integer'
    ];

    // Relación con Evento
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Accessor para el status
    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? 'Activa' : 'Inactiva';
    }

    public function getStatusBadgeClassAttribute()
    {
        return $this->status == 1 ? 'bg-success' : 'bg-danger';
    }

    // Accessor para el género (ya viene en MAYÚSCULAS de la BD)
    public function getGenderLabelAttribute()
    {
        return $this->gender_restriction ?? 'N/A';
    }

    // Scope para activas
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope para inactivas
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    /**
 * Verifica si un atleta es elegible para esta categoría
 */
public function isAthleteEligible($athlete, $eventDate = null)
{
    // Validar que el atleta tenga fecha de nacimiento
    if (!$athlete || !$athlete->birth_date) {
        return [
            'eligible' => false,
            'message' => 'El atleta no tiene fecha de nacimiento registrada.'
        ];
    }

    // Obtener fecha del evento
    if (!$eventDate && $this->event) {
        $eventDate = $this->event->event_date;
    }

    if (!$eventDate) {
        return [
            'eligible' => false,
            'message' => 'No se pudo determinar la fecha del evento.'
        ];
    }

    // Calcular edad del atleta en la fecha del evento
    $birthDate = \Carbon\Carbon::parse($athlete->birth_date);
    $eventDate = \Carbon\Carbon::parse($eventDate);
    $age = $birthDate->diffInYears($eventDate);

    // Validar edad mínima
    if ($this->min_age !== null && $age < $this->min_age) {
        return [
            'eligible' => false,
            'message' => "Edad insuficiente: {$age} años (mínimo {$this->min_age} años)",
            'age' => $age
        ];
    }

    // Validar edad máxima
    if ($this->max_age !== null && $age > $this->max_age) {
        return [
            'eligible' => false,
            'message' => "Edad excedida: {$age} años (máximo {$this->max_age} años)",
            'age' => $age
        ];
    }

    // Validar género (si la categoría tiene restricción)
    if ($this->gender_restriction !== null && $athlete->gender !== $this->gender_restriction) {
        $genderLabel = $this->gender_restriction === 'femenino' ? 'femenino' : 'masculino';
        return [
            'eligible' => false,
            'message' => "Género no permitido: requiere {$genderLabel}",
            'age' => $age
        ];
    }

    // Si pasa todas las validaciones
    return [
        'eligible' => true,
        'message' => 'Cumple con todos los requisitos',
        'age' => $age
    ];
}

    
}