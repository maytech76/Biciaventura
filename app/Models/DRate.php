<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DRate extends Model
{
    protected $table = 'drates';
    
    protected $fillable = [
        'moneda',
        'fuente',
        'compra',
        'venta',
        'promedio',
        'fecha_actualizacion_api',
        'fecha_registro'
    ];
    
    protected $casts = [
        'compra' => 'decimal:4',
        'venta' => 'decimal:4',
        'promedio' => 'decimal:4',
        'fecha_actualizacion_api' => 'datetime',
        'fecha_registro' => 'datetime'
    ];
    
    public static function getUltimaTasa($moneda = 'USD', $fuente = 'oficial'){

        return self::where('moneda', $moneda)
            ->where('fuente', $fuente)
            ->orderBy('fecha_registro', 'desc')
            ->first();
    }
    
    public static function getHistorial($dias = 30, $moneda = 'USD', $fuente = 'oficial'){

        return self::where('moneda', $moneda)
            ->where('fuente', $fuente)
            ->where('fecha_registro', '>=', now()->subDays($dias))
            ->orderBy('fecha_registro', 'desc')
            ->get();
    }
    
    public static function registrarSiCambio($cotizacion){
        
        $ultimaTasa = self::getUltimaTasa($cotizacion['moneda'], $cotizacion['fuente']);
        
        if (!$ultimaTasa || $ultimaTasa->promedio != $cotizacion['promedio']) {
            return self::create([
                'moneda' => $cotizacion['moneda'],
                'fuente' => $cotizacion['fuente'],
                'compra' => $cotizacion['compra'],
                'venta' => $cotizacion['venta'],
                'promedio' => $cotizacion['promedio'],
                'fecha_actualizacion_api' => $cotizacion['fechaActualizacion'],
                'fecha_registro' => now()
            ]);
        }
        
        return $ultimaTasa;
    }
}