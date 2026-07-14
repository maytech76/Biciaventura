<?php

namespace App\Helpers;

use App\Services\DolarApiService;

class DolarHelper
{
    protected static $service;
    
    public static function getService()
    {
        if (!self::$service) {
            self::$service = app(DolarApiService::class);
        }
        return self::$service;
    }

    /**
     * Obtener tasa actual de una moneda específica
     * @param string $moneda USD, EUR
     * @param string $fuente oficial, paralelo
     */
    public static function getTasa($moneda = 'USD', $fuente = 'oficial'){

        try {
            $tasa = self::getService()->getTasaActual($moneda, $fuente);
            return [
                'rate' => $tasa->promedio,
                'compra' => $tasa->compra,
                'venta' => $tasa->venta,
                'updated_at' => $tasa->fecha_actualizacion_api,
                'registered_at' => $tasa->fecha_registro,
                'formatted' => 'Bs. ' . number_format($tasa->promedio, 2)
            ];
        } catch (\Exception $e) {
            return null;
        }
    }


     /* Obtener Tasa del Dolar actual, con fallback a la última tasa local en caso de error */
    public static function getDolarRate(){

        try {
            $tasa = self::getService()->getDolarOficial();
            return $tasa['rate'];
        } catch (\Exception $e) {
            $ultimaTasa = \App\Models\DRate::getUltimaTasa();
            return $ultimaTasa ? $ultimaTasa->promedio : 0;
        }
    }
    
    
    //Convierte un precio en USD a Bs usando la tasa actual
    public static function toBs($priceInUsd){

        $rate = self::getDolarRate();
        return $priceInUsd * $rate;
    }


    // formatear con el símbolo de BS y dos decimales
    public static function formatBs($priceInUsd){
        $priceInBs = self::toBs($priceInUsd);
        return 'Bs. ' . number_format($priceInBs, 2);
    }

    
    /* Formatear precios a Dolar / Dos Decimales*/
    public static function formatUsd($priceInUsd){

        return '$ ' . number_format($priceInUsd, 2);
    }

    
    /* Mostrar ambos precios (dólares y bolívares) */
    public static function formatBoth($priceInUsd){
        
        $usd = self::formatUsd($priceInUsd);
        $bs = self::formatBs($priceInUsd);
        return "{$usd} / {$bs}";
    }


    /*** Convertir de bolívares a dólares ***/
    public static function toUsd($priceInBs){
        $rate = self::getDolarRate();
        return $rate > 0 ? $priceInBs / $rate : 0;
    }

    
    public static function getTasaInfo(){
        
        try {
            $tasa = self::getService()->getDolarOficial();
            return [
                'rate' => $tasa['rate'],
                'updated_at' => $tasa['updated_at'],
                'formatted' => 'Bs. ' . number_format($tasa['rate'], 2)
            ];
        } catch (\Exception $e) {
            $ultimaTasa = \App\Models\DRate::getUltimaTasa();
            if ($ultimaTasa) {
                return [
                    'rate' => $ultimaTasa->promedio,
                    'updated_at' => $ultimaTasa->fecha_registro,
                    'formatted' => 'Bs. ' . number_format($ultimaTasa->promedio, 2)
                ];
            }
            return null;
        }
    }

    /*------------------------
     |---- Tasa Moneda Euro --|
      ------------------------ */

    /*** Obtener información completa de la tasa del euro ***/
    public static function getEuroRate(){

        $tasa = self::getTasa('EUR', 'oficial');
        return $tasa ? $tasa['rate'] : 0;
    }

    /*** Convertir de euros a bolívares ***/
    public static function euroToBs($priceInEuro){
        $rate = self::getEuroRate();
        return $priceInEuro * $rate;
    }

    /*** Convertir de bolívares a euros ***/
    public static function toEuro($priceInBs){

        $rate = self::getEuroRate();
        return $rate > 0 ? $priceInBs / $rate : 0;
    }



}