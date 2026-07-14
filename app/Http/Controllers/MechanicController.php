<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mechanics = Mechanic::all();
        return view('admin.mechanics.index', compact('mechanics'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $mechanic = Mechanic::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $mechanic
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mecánico no encontrado'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'document' => 'required|string|max:15|unique:mechanics,document',
                'full_name' => 'required|string|max:100',
                'phone' => 'nullable|string|max:15',
                'commission' => 'nullable|numeric|min:0|max:99.99',
                'email' => 'required|email|max:100|unique:mechanics,email',
                'status' => 'boolean',
            ]);

            $mechanic = Mechanic::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Mecánico creado exitosamente',
                'data' => $mechanic
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el mecánico: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){

        try {
            $mechanic = Mechanic::findOrFail($id);
            
            $validated = $request->validate([
                'document' => 'required|string|max:15|unique:mechanics,document,' . $id,
                'full_name' => 'required|string|max:100',
                'phone' => 'nullable|string|max:15',
                'commission' => 'nullable|numeric|min:0|max:99.99',
                'email' => 'required|email|max:100|unique:mechanics,email,' . $id,
                'status' => 'boolean',
            ]);

            $mechanic->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Mecánico actualizado exitosamente',
                'data' => $mechanic
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el mecánico'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        
        try {
            $mechanic = Mechanic::findOrFail($id);
            $mechanic->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mecánico eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el mecánico'
            ], 500);
        }
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus($id){
        try {
            $mechanic = Mechanic::findOrFail($id);
            $mechanic->status = !$mechanic->status;
            $mechanic->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'data' => $mechanic
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado'
            ], 500);
        }
    }
}