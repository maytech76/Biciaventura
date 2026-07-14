<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "registrations";

    protected $fillable = [
        'athlete_id',
        'event_id',
        'event_category_id',
        'code',
        'image', // ✅ Nuevo campo
        'payment_method',
        'payment_reference',
        'amount',
        'status',
        'notes',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // ========== ACCESORES ==========
    
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/registrations/' . $this->image);
        }
        return null;
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'inscrito' => 'Inscrito',
            'pendiente' => 'Pendiente',
            'confirmado' => 'Confirmado',
            'retirado' => 'Retirado',
            default => 'Desconocido',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'inscrito' => 'primary',
            'pendiente' => 'warning',
            'confirmado' => 'success',
            'retirado' => 'danger',
            default => 'secondary',
        };
    }

    public function getPaymentMethodLabelAttribute(){
        
        return match($this->payment_method) {
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta' => 'Tarjeta',
            'otros' => 'Otros',
            default => 'No especificado',
        };
    }

    // ========== RELACIONES ==========
    
    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    // ========== SCOPES ==========
    
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByAthlete($query, $athleteId)
    {
        return $query->where('athlete_id', $athleteId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmado');
    }

    // ========== MÉTODOS ==========
    
    /**
     * Genera un código único para la inscripción
     * Formato: [PREFIX][NÚMERO]
     * Ejemplo: MTA0001, MTB0001, ROU0001
     */
    public static function generateCode($categoryName, $eventId)
    {
        // Obtener el prefijo de la categoría
        $prefix = self::getCategoryPrefix($categoryName);
        
        // Buscar la última inscripción con el mismo prefijo en el mismo evento
        $lastRegistration = self::where('event_id', $eventId)
            ->where('code', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastRegistration) {
            // Extraer el número del último código
            $lastNumber = (int) substr($lastRegistration->code, strlen($prefix));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $newNumber;
    }

    /**
     * Obtiene el prefijo de la categoría basado en su nombre
     */
    private static function getCategoryPrefix($categoryName)
    {
        // Mapeo de categorías a prefijos
        $prefixMap = [
            'MASTER A' => 'MTA',
            'MASTER B' => 'MTB',
            'MASTER C' => 'MTC',
            'ELITE' => 'ELI',
            'JUNIOR' => 'JUN',
            'SUB 23' => 'SUB',
            'INFANTIL' => 'INF',
            'CADETE' => 'CAD',
            'JUVENIL' => 'JUV',
            'SENIOR' => 'SEN',
            'VETERANO' => 'VET',
            'FEMENINO' => 'FEM',
            'MASCULINO' => 'MAS',
            'OPEN' => 'OPN',
            'PRINCIPIANTE' => 'PRI',
            'AVANZADO' => 'AVA',
            'EXPERTO' => 'EXP',
            // Deportes específicos
            'MTB' => 'MTB',
            'ROUTE' => 'ROU',
            'DOWNHILL' => 'DOW',
            'ENDURO' => 'END',
            'SPORT' => 'SPO',
            // Ciclismo
            'RUTA' => 'RUT',
            'MONTAÑA' => 'MON',
            'BMX' => 'BMX',
            'TRIAL' => 'TRI',
            'CICLOCROSS' => 'CCX',
            'GRAVEL' => 'GRA',
            'URBANO' => 'URB',
            'PISTA' => 'PIS',
            'CRONO' => 'CRO',
            'ESCALADA' => 'ESC',
            'DESCENSO' => 'DES',
            'XC' => 'XCC',
            'MARATON' => 'MAR',
            'FONDO' => 'FON',
            'CRITERIUM' => 'CRI',
            'VELOCIDAD' => 'VEL',
            'RESISTENCIA' => 'RES',
            'SPRINT' => 'SPR',
            'RELEVOS' => 'REL',
        ];

        // Limpiar y normalizar el nombre de la categoría
        $cleanName = strtoupper(trim($categoryName));
        
        // Buscar coincidencia exacta
        if (isset($prefixMap[$cleanName])) {
            return $prefixMap[$cleanName];
        }
        
        // Buscar coincidencia parcial (primera palabra)
        $words = explode(' ', $cleanName);
        if (isset($words[0]) && isset($prefixMap[$words[0]])) {
            return $prefixMap[$words[0]];
        }
        
        // Si no encuentra coincidencia, generar prefijo con las primeras 3 letras
        return substr(preg_replace('/[^A-Z]/', '', $cleanName), 0, 3);
    }

    /**
     * Verificar si el atleta es elegible para esta categoría
     */
    public static function isEligible($athleteId, $categoryId){

        $athlete = Athlete::find($athleteId);
        $category = EventCategory::find($categoryId);
        
        if (!$athlete || !$category) {
            return false;
        }

        $age = $athlete->birth_date->age;
        if ($category->min_age && $age < $category->min_age) {
            return false;
        }
        if ($category->max_age && $age > $category->max_age) {
            return false;
        }

        if ($category->gender_restriction && $category->gender_restriction !== $athlete->gender) {
            return false;
        }

        return true;
    }

    /**
     * Obtener categorías disponibles para un atleta en un evento
     */
    public static function getAvailableCategories($athleteId, $eventId){

        $athlete = Athlete::find($athleteId);
        if (!$athlete) {
            return collect();
        }

        $age = $athlete->birth_date->age;
        $gender = $athlete->gender;

        return EventCategory::where('event_id', $eventId)
            ->where('status', 1)
            ->where(function($query) use ($age, $gender) {
                $query->where(function($q) use ($age) {
                    $q->whereNull('min_age')
                      ->orWhere('min_age', '<=', $age);
                })
                ->where(function($q) use ($age) {
                    $q->whereNull('max_age')
                      ->orWhere('max_age', '>=', $age);
                });
            })
            ->where(function($query) use ($gender) {
                $query->whereNull('gender_restriction')
                      ->orWhere('gender_restriction', $gender);
            })
            ->get();
    }

    


}