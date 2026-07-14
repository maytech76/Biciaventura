<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Athlete extends Model
{
  

    protected $fillable = [

        'photo',
        'document',
        'name',
        'last_name',
        'birth_date',
        'gender',
        'phone',
        'state_id',
        'city_id',
        'email',
        'team_name',
        'medical_conditions',
        'allergies',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'status',
    ];

    protected $casts = [

        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        
    ];

    // ========== RELACIONES ==========
    
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Relaciones futuras (cuando las necesites)
    public function eventRegistrations(){

        /* return $this->hasMany(EventRegistration::class); */
    } 

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    // ========== ACCESORES ==========
    
    public function getFullNameAttribute() /* Nombre completo */
    {
        return "{$this->name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return $this->birth_date->age;
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/athletes/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }

    public function getGenderLabelAttribute()
    {
        return match($this->gender) {

            'masculino' => 'Masculino',
            'femenino' => 'Femenino',
            
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {

            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
            'suspendido' => 'Suspendido',
            
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {

            'activo' => 'success',
            'inactivo' => 'warning',
            'suspendido' => 'danger',
            default => 'secondary',
        };
    }

    // ========== SCOPES ==========
    
    // Scope para atletas activos
    public function scopeActive($query)
    {
        return $query->where('status', 'activo');
    }

    // Scope para atletas inactivos
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactivo');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%")
              ->orWhere('document', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('team_name', 'LIKE', "%{$search}%");
        });
    }

    public function scopeByState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }

    public function scopeByTeam($query, $teamName)
    {
        return $query->where('team_name', 'LIKE', "%{$teamName}%");
    }

    public function scopeByAgeRange($query, $minAge, $maxAge)
    {
        $minDate = now()->subYears($maxAge)->startOfDay();
        $maxDate = now()->subYears($minAge)->endOfDay();
        return $query->whereBetween('birth_date', [$minDate, $maxDate]);
    }

    // ========== MUTADORES ==========
    
    public function setDocumentAttribute($value)
    {
        $this->attributes['document'] = strtoupper(trim($value));
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower(trim($value)));
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords(strtolower(trim($value)));
    }
}