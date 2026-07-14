<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    public function index(){
        $cities = City::withCount('zones')->latest()->paginate(4);
        return view('cities.index', compact('cities'));
    } 

    public function create(){
        return view('cities.create');
    }

    public function store(Request $request){
        $request->validate([
            'city_name' => 'required|string|max:255',
            'zones' => 'required|array|min:1',
            'zones.*.name' => 'required|string|max:255',
            'zones.*.price' => 'required|numeric|min:0',
        ]);

        $city = City::create([
            'name' => $request->city_name
        ]);

        foreach ($request->zones as $zoneData) {
            Zone::create([
                'name' => $zoneData['name'],
                'price' => $zoneData['price'],
                'city_id' => $city->id
            ]);
        }

        return redirect()->route('cities.index')
            ->with('success', 'Ciudad y zonas creadas exitosamente.');
    }

    public function show(City $city){
        Log::info('🔍 CityController@show llamado', [
            'city_id' => $city->id,
            'city_name' => $city->name
        ]);
        
        // Cargar las zonas con paginación
        $zones = $city->zones()->paginate(10);
        
        Log::info('📊 Zonas encontradas', [
            'total_zones' => $zones->total(),
            'current_page' => $zones->currentPage()
        ]);
        
        return response()->json([
            'success' => true,
            'city' => [
                'id' => $city->id,
                'name' => $city->name,
                'status' => $city->status
            ],
            'zones' => $zones->items(),
            'pagination' => [
                'current_page' => $zones->currentPage(),
                'last_page' => $zones->lastPage(),
                'per_page' => $zones->perPage(),
                'total' => $zones->total(),
                'next_page_url' => $zones->nextPageUrl(),
                'prev_page_url' => $zones->previousPageUrl()
            ]
        ]);
    }

    /* consultar ciudad y mostrar sus Zonas */
    public function getCityDetails($id){
        try {
            $city = City::withCount(['zones' => function($query) {
                $query->where('status', 1); // Solo zonas activas si quieres filtrar
            }])->with(['zones' => function($query) {
                $query->select('id', 'name', 'price', 'status', 'city_id')
                      ->orderBy('name');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $city->id,
                    'name' => $city->name,
                    'status' => $city->status,
                    'zones_count' => $city->zones_count,
                    'created_at' => $city->created_at->format('d/m/Y'),
                    'zones' => $city->zones->map(function($zone) {
                        return [
                            'name' => $zone->name,
                            'price' => number_format($zone->price, 2),
                            'status' => $zone->status,
                            'status_badge' => $zone->status == 1 
                                ? '<span class="badge bg-success">Activa</span>' 
                                : '<span class="badge bg-danger">Inactiva</span>'
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles de la ciudad'
            ], 404);
        }
    }

   /*  public function getZones($cityId){
        $zones = Zone::where('city_id', $cityId)
            ->orderBy('price')
            ->paginate(10)
            ->get();

        return response()->json($zones);
    } */
}