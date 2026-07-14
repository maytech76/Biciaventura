<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function index(){
        
        $vehicles = Vehicle::with(['brand', 'user'])->get();
        $users = User::where('role', 'customer')->get();
        $brands = Brand::all();

        return view('admin.vehicles.index', compact('vehicles', 'users', 'brands'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:Mtb,Ruta,Enduro,Bmx,Niños,E-Bike',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:100',
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Vehicle::create([
            'type' => $request->type,
            'brand_id' => $request->brand_id,
            'model' => $request->model,
            'user_id' => $request->user_id,
            'status' => $request->status ?? true
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Bicicleta creada exitosamente.');
    }

    public function edit($id){

        $vehicle = Vehicle::with(['brand', 'user'])->findOrFail($id);
        $brands = Brand::all();
        $users = User::where('role', 'customer')->get();

        return response()->json([
            'vehicle' => $vehicle,
            'brands' => $brands,
            'users' => $users
        ]);
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:Mtb,Ruta,Enduro,Bmx,Niños,E-Bike',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:100',
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vehicle = Vehicle::findOrFail($id);
        
        $vehicle->update([
            'type' => $request->type,
            'brand_id' => $request->brand_id,
            'model' => $request->model,
            'user_id' => $request->user_id,
            'status' => $request->status ?? $vehicle->status
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Bicicleta actualizada exitosamente.');
    }

    public function destroy($id){
        $vehicle = Vehicle::findOrFail($id);
        
        // Desactivar en lugar de eliminar
        $vehicle->update(['status' => false]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Bicicleta desactivada exitosamente.');
    }
}