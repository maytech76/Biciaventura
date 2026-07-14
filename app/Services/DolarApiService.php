<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\DRate;

class DolarApiService
{
    protected $baseUrl = 'https://ve.dolarapi.com/v1';
    
    public function getCotizacionesFromApi()
    {
        $response = Http::timeout(10)->get("{$this->baseUrl}/cotizaciones");
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Error al obtener cotizaciones: ' . $response->status());
    }
    
    public function getCotizacionByMonedaFromApi($moneda)
    {
        $cotizaciones = $this->getCotizacionesFromApi();
        return collect($cotizaciones)->firstWhere('moneda', strtoupper($moneda));
    }
    
    public function getTasaActual($moneda = 'USD', $fuente = 'oficial')
    {
        try {
            $cotizacionApi = $this->getCotizacionByMonedaFromApi($moneda);
            
            if (!$cotizacionApi) {
                throw new \Exception("No se encontró cotización para {$moneda}");
            }
            
            $tasa = DRate::registrarSiCambio($cotizacionApi);
            return $tasa;
            
        } catch (\Exception $e) {
            $tasaLocal = DRate::getUltimaTasa($moneda, $fuente);
            
            if ($tasaLocal) {
                return $tasaLocal;
            }
            
            throw new \Exception('Error al obtener tasa: ' . $e->getMessage());
        }
    }
    
    public function getDolarOficial()
    {
        $tasa = $this->getTasaActual('USD', 'oficial');
        
        return [
            'rate' => $tasa->promedio,
            'compra' => $tasa->compra,
            'venta' => $tasa->venta,
            'updated_at' => $tasa->fecha_actualizacion_api,
            'registered_at' => $tasa->fecha_registro
        ];
    }
    
    public function getTodasLasTasas()
    {
        $cotizacionesApi = $this->getCotizacionesFromApi();
        $resultados = [];
        
        foreach ($cotizacionesApi as $cotizacion) {
            $tasa = DRate::registrarSiCambio($cotizacion);
            $resultados[] = [
                'moneda' => $tasa->moneda,
                'fuente' => $tasa->fuente,
                'promedio' => $tasa->promedio,
                'compra' => $tasa->compra,
                'venta' => $tasa->venta,
                'fecha_actualizacion' => $tasa->fecha_actualizacion_api,
                'fecha_registro' => $tasa->fecha_registro
            ];
        }
        
        return $resultados;
    }
}