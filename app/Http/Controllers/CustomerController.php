<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\City;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        // ✅ CORREGIDO: Filtrar por role = 'customer'
        $users = User::where('role', 'customer')
            ->with('city')  // Cargar la relación con ciudad
            ->orderBy('name')
            ->get();
        
        $cities = City::orderBy('name')->get();
        
        return view('admin.customers.index', compact('users', 'cities'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request){

        // Validación
        $validator = Validator::make($request->all(), [
            'document' => 'required|string|max:15|unique:users,document',
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:150|unique:users,email',
            'city_id' => 'nullable|exists:cities,id',
            'status' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return redirect()->route('customers.index')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Error de validación');
        }

        // Crear el cliente con role = 'customer'
        $user = User::create([
            'document' => $request->document,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city_id' => $request->city_id,
            'status' => $request->status,
            'role' => 'customer',  // ✅ IMPORTANTE: Asignar el rol
            'password' => Hash::make('password123'),  // Contraseña por defecto
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Cliente registrado exitosamente');
    }

    /**
     * Display the specified customer.
     */
    public function show($id)
    {
        $user = User::with('city')->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Get customer data for editing.
     */
    public function edit($id){

        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, $id){

        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'document' => 'required|string|max:15|unique:users,document,' . $id,
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:150|unique:users,email,' . $id,
            'city_id' => 'nullable|exists:cities,id',
            'password' => 'password123',
            'status' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return redirect()->route('customers.index')
                ->withErrors($validator)
                ->with('edit_error', true)
                ->withInput()
                ->with('error', 'Error de validación');
        }

        $user->update([
            'document' => $request->document,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city_id' => $request->city_id,
            'status' => $request->status
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    /**
     * Remove the specified customer (soft delete or deactivate).
     */
    public function destroy($id){

        $user = User::findOrFail($id);
        
        // Desactivar el cliente (status = 0)
        $user->update(['status' => 0]);
        
        return redirect()->route('customers.index')
            ->with('success', 'Cliente desactivado exitosamente');
    }

    /**
     * Activate a customer.
     */
    public function activate($id){
        $user = User::findOrFail($id);
        $user->update(['status' => 1]);
        
        return redirect()->route('customers.index')
            ->with('success', 'Cliente activado exitosamente');
    }

    /**
     * Get customers list for AJAX.
     */
    public function getCustomers(){
        $users = User::where('role', 'customer')
            ->with('city')
            ->orderBy('name')
            ->get();
        
        return response()->json($users);
    }
}