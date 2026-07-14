<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [

        'user_id',
        'banner',
        'type',
        'name',
        'description',
        'event_date',
        'state_id',             
        'registration_deadline',  // ← ESTE ESTABA FALTANDO
        'city_id',
        'address',
        'phone',
        'name_manager',
        'phone_manager',
        'email_manager',
        'status'


    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    // Relaciones
    public function user(){

        return $this->belongsTo(User::class);
    }

    public function eventCategories(){

       return $this->hasMany(EventCategory::class);
    }

    public function state(){

        return $this->belongsTo(State::class);
    }

    public function city(){

        return $this->belongsTo(City::class);
    }


    /* Helper para el campo status y sys valores emun */
    public static function getStatusOptions(){

        return [
            'draft' => 'Borrador',
            'published' => 'Publicado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado'
        ];
    }

    /* permanece enviando el atrubuto del campo status */
    public function getStatusLabelAttribute(){

        $statuses = self::getStatusOptions();
        return $statuses[$this->status] ?? $this->status;
    }

    public function scopeUpcoming($query){
        
        return $query->where('event_date', '>=', now());
    }

}
