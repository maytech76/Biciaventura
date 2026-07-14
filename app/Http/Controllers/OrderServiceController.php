<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use App\Models\Product;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class OrderServiceController extends Controller
{
    /*** Display a listing of the resource ***/
    public function index(){

      $users = User::where('role', 'customer')->get();
      $vehicles = Vehicle::where('user_id', 1)->get();
      $Mechanics = Mechanic::where('status', 1)->get();
      $products = Product::all();

      return view('admin.order_services.index', compact('users', 'vehicles', 'Mechanics', 'products'));

        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        
        $users = User::where('role', 'customer')
                 ->where('status', 1)
                 ->orderBy('name')
                 ->get();
        $vehicles = Vehicle::where('user_id', 1)->get();
        $Mechanics = Mechanic::where('status', 1)->get();
        $products = Product::all();
  

      return view('admin.order_services.create', compact('users', 'vehicles', 'Mechanics', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getVehiclesByUser($userId){

        try {
            // Verificar que el usuario existe
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
            
            // Obtener los vehículos del usuario con la relación de marca
            $vehicles = Vehicle::with('brand')
                            ->where('user_id', $userId)
                            ->where('status', true) // Solo vehículos activos
                            ->get();
            
            // Formatear los datos para el frontend
            $formattedVehicles = $vehicles->map(function($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'model' => $vehicle->model, // Este será el nombre visible
                    'type' => $vehicle->type,
                    'brand_name' => $vehicle->brand->name ?? 'Sin marca',
                    'brand_id' => $vehicle->brand_id,
                    'created_at' => $vehicle->created_at ? $vehicle->created_at->format('d-m-Y') : null,
                    'status' => $vehicle->status
                ];
            });
            
            return response()->json($formattedVehicles);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los vehículos: ' . $e->getMessage()], 500);
        }
    }

    public function getUserInfo($userId){

        try {
            // Buscar el usuario con su relación de ciudad
            $user = User::with('city')->find($userId);
            
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
            
            // Verificar que sea un cliente
            if ($user->role !== 'customer') {
                return response()->json(['error' => 'El usuario no es un cliente'], 400);
            }
            
            // Retornar la información formateada
            return response()->json([
                'name' => $user->name,
                'document' => $user->document ?? 'No registrado',
                'email' => $user->email,
                'phone' => $user->phone ?? 'No registrado',
                'address' => $user->address ?? 'No registrada',
                'city' => $user->city->name ?? 'No especificada',
                'status' => $user->status,
                'role' => $user->role
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la información del usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductPrice($productId){

        try {
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
            
            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price ?? 0
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el precio'], 500);
        }
    }
}
