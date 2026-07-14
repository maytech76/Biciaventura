<?php

namespace App\Http\Controllers;

use App\Services\DolarApiService;
use App\Models\DRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DolarController extends Controller
{
    protected $dolarService;
    
    public function __construct(DolarApiService $dolarService){

        $this->dolarService = $dolarService;
    }
    
   /**
     * Muestra la página principal de tasas de cambio
     * 
     * @return \Illuminate\View\View
     */
   /**
     * Muestra la página principal de tasas de cambio
     * 
     * Obtiene y prepara los datos necesarios para la vista:
     * - Tasas actuales de USD y EUR desde la API/BD
     * - Historial de los últimos 7 días para ambas monedas
     * - Datos para el conversor de monedas
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // ===========================================================
            // 1. OBTENER TASAS ACTUALES
            // ===========================================================
            // Estos métodos verifican automáticamente si hay cambios en la API
            // Si el valor cambió, se registra automáticamente en la tabla drates
            $tasaUSD = $this->dolarService->getTasaActual('USD', 'oficial');
            $tasaEUR = $this->dolarService->getTasaActual('EUR', 'oficial');
            
            // ===========================================================
            // 2. OBTENER TODAS LAS TASAS ACTUALIZADAS
            // ===========================================================
            // Array con todas las monedas disponibles (USD y EUR)
            $todasTasas = $this->dolarService->getTodasLasTasas();
            
            // ===========================================================
            // 3. OBTENER HISTORIAL COMPLETO
            // ===========================================================
            // Últimos 7 días de registros de todas las monedas
            $historial = DRate::where('fecha_registro', '>=', now()->subDays(7))
                ->orderBy('fecha_registro', 'desc')
                ->get();
            
            // ===========================================================
            // 4. SEPARAR HISTORIAL POR MONEDA
            // ===========================================================
            // Filtramos los registros para facilitar su uso en la vista
            $historialUSD = $historial->where('moneda', 'USD');
            $historialEUR = $historial->where('moneda', 'EUR');
            
            // ===========================================================
            // 5. DATOS ADICIONALES PARA LA VISTA
            // ===========================================================
            // Calculamos información útil como fecha de última actualización
            $ultimaActualizacion = $historial->isNotEmpty() 
                ? $historial->first()->fecha_registro 
                : null;
            
            $infoTasas = [
                'usd' => [
                    'tasa' => $tasaUSD ? $tasaUSD->promedio : 0,
                    'compra' => $tasaUSD ? $tasaUSD->compra : null,
                    'venta' => $tasaUSD ? $tasaUSD->venta : null,
                    'fecha' => $tasaUSD ? $tasaUSD->fecha_actualizacion_api : null,
                ],
                'eur' => [
                    'tasa' => $tasaEUR ? $tasaEUR->promedio : 0,
                    'compra' => $tasaEUR ? $tasaEUR->compra : null,
                    'venta' => $tasaEUR ? $tasaEUR->venta : null,
                    'fecha' => $tasaEUR ? $tasaEUR->fecha_actualizacion_api : null,
                ]
            ];
            
            // ===========================================================
            // 6. REGISTRO DE LOG (OPCIONAL)
            // ===========================================================
            // Útil para monitorear el funcionamiento en producción
            Log::info('Tasas consultadas', [
                'usd' => $infoTasas['usd']['tasa'],
                'eur' => $infoTasas['eur']['tasa'],
                'registros_historial' => $historial->count(),
                'timestamp' => now()
            ]);
            
            // ===========================================================
            // 7. RETORNAR VISTA CON DATOS
            // ===========================================================
            return view('dolar.index', compact(
                'tasaUSD',
                'tasaEUR',
                'todasTasas',
                'historial',
                'historialUSD',
                'historialEUR',
                'infoTasas',
                'ultimaActualizacion'
            ));
            
        } catch (\Exception $e) {
            // ===========================================================
            // 8. MANEJO DE ERRORES
            // ===========================================================
            // Registrar error detallado en el log
            Log::error('Error en DolarController@index', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Intentar obtener datos de respaldo desde la BD si la API falla
            $tasaUSDRespaldo = DRate::getUltimaTasa('USD', 'oficial');
            $tasaEURRespaldo = DRate::getUltimaTasa('EUR', 'oficial');
            
            if ($tasaUSDRespaldo || $tasaEURRespaldo) {
                // Si hay datos de respaldo, mostrar advertencia pero continuar
                return view('dolar.index', [
                    'tasaUSD' => $tasaUSDRespaldo,
                    'tasaEUR' => $tasaEURRespaldo,
                    'todasTasas' => [],
                    'historial' => collect([]),
                    'historialUSD' => collect([]),
                    'historialEUR' => collect([]),
                    'infoTasas' => [
                        'usd' => ['tasa' => $tasaUSDRespaldo ? $tasaUSDRespaldo->promedio : 0],
                        'eur' => ['tasa' => $tasaEURRespaldo ? $tasaEURRespaldo->promedio : 0]
                    ],
                    'ultimaActualizacion' => null,
                    'warning' => 'Usando datos de respaldo. La API no está disponible.'
                ])->with('warning', 'Las tasas mostradas son de la última actualización disponible. La API no responde.');
            }
            
            // Si no hay datos de respaldo, mostrar error
            return back()->with('error', 'Error al obtener tasas de cambio. Por favor, intente más tarde.');
        }
    }

     /**
     * Obtener tasa específica por moneda (API endpoint)
     */
    public function getRate(Request $request)
    {
        try {
            $moneda = $request->get('moneda', 'USD');
            $fuente = $request->get('fuente', 'oficial');
            
            $tasa = $this->dolarService->getTasaActual($moneda, $fuente);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'moneda' => $tasa->moneda,
                    'fuente' => $tasa->fuente,
                    'promedio' => $tasa->promedio,
                    'compra' => $tasa->compra,
                    'venta' => $tasa->venta,
                    'fecha_actualizacion_api' => $tasa->fecha_actualizacion_api,
                    'fecha_registro_sistema' => $tasa->fecha_registro
                ]
            ]);
        } catch (\Exception $e) {
            $tasaLocal = DRate::getUltimaTasa($request->get('moneda', 'USD'));
            
            if ($tasaLocal) {
                return response()->json([
                    'success' => true,
                    'warning' => 'Usando tasa local por error de API',
                    'data' => [
                        'moneda' => $tasaLocal->moneda,
                        'fuente' => $tasaLocal->fuente,
                        'promedio' => $tasaLocal->promedio,
                        'compra' => $tasaLocal->compra,
                        'venta' => $tasaLocal->venta,
                        'fecha_actualizacion_api' => $tasaLocal->fecha_actualizacion_api,
                        'fecha_registro_sistema' => $tasaLocal->fecha_registro
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Recibirla tasa actual por API, con opción a fallback a la última tasa local en caso de error
    public function getCurrentRate(Request $request)
    {
        try {
            $moneda = $request->get('moneda', 'USD');
            $fuente = $request->get('fuente', 'oficial');
            
            $tasa = $this->dolarService->getTasaActual($moneda, $fuente);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'moneda' => $tasa->moneda,
                    'fuente' => $tasa->fuente,
                    'promedio' => $tasa->promedio,
                    'compra' => $tasa->compra,
                    'venta' => $tasa->venta,
                    'fecha_actualizacion_api' => $tasa->fecha_actualizacion_api,
                    'fecha_registro_sistema' => $tasa->fecha_registro
                ]
            ]);
        } catch (\Exception $e) { //en caso de fellar la API,  obtener la última tasa local
            $tasaLocal = DRate::getUltimaTasa($request->get('moneda', 'USD'));
            
            if ($tasaLocal) {
                return response()->json([
                    'success' => true,
                    'warning' => 'Usando tasa local por error de API',
                    'data' => [
                        'moneda' => $tasaLocal->moneda,
                        'fuente' => $tasaLocal->fuente,
                        'promedio' => $tasaLocal->promedio,
                        'compra' => $tasaLocal->compra,
                        'venta' => $tasaLocal->venta,
                        'fecha_actualizacion_api' => $tasaLocal->fecha_actualizacion_api,
                        'fecha_registro_sistema' => $tasaLocal->fecha_registro
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //historial de tasas x los ultimos 30 dias 
    /* public function getHistorial(Request $request){

        $dias = $request->get('dias', 30);
        $moneda = $request->get('moneda', 'USD');
        $fuente = $request->get('fuente', 'oficial');
        
        $historial = DRate::getHistorial($dias, $moneda, $fuente);
        
        return response()->json([
            'success' => true,
            'data' => $historial
        ]);
    } */
    
    //actualizar la tasa manualmente, forzando la consulta a la API y registrando solo si hubo cambio
    /* public function forceUpdate(){ 
        try {
            $cotizaciones = $this->dolarService->getCotizacionesFromApi();
            $actualizados = [];
            
            foreach ($cotizaciones as $cotizacion) {
                $registro = DRate::registrarSiCambio($cotizacion);
                $actualizados[] = [
                    'moneda' => $registro->moneda,
                    'promedio' => $registro->promedio,
                    'actualizado' => $registro->wasRecentlyCreated
                ];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Actualización completada',
                'data' => $actualizados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    } */

    /**
     * Obtener todas las tasas (API endpoint)
     */
    public function getAllRates()
    {
        try {
            $tasas = $this->dolarService->getTodasLasTasas();
            
            return response()->json([
                'success' => true,
                'data' => $tasas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtener historial por moneda (API endpoint)
     */
    public function getHistorial(Request $request)
    {
        $dias = $request->get('dias', 30);
        $moneda = $request->get('moneda', 'USD');
        $fuente = $request->get('fuente', 'oficial');
        
        $historial = DRate::getHistorial($dias, $moneda, $fuente);
        
        return response()->json([
            'success' => true,
            'data' => $historial
        ]);
    }
    
    /**
     * Forzar actualización manual
     */
    public function forceUpdate()
    {
        try {
            $cotizaciones = $this->dolarService->getCotizacionesFromApi();
            $actualizados = [];
            
            foreach ($cotizaciones as $cotizacion) {
                $registro = DRate::registrarSiCambio($cotizacion);
                $actualizados[] = [
                    'moneda' => $registro->moneda,
                    'promedio' => $registro->promedio,
                    'actualizado' => $registro->wasRecentlyCreated
                ];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Actualización completada',
                'data' => $actualizados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


}