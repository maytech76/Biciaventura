<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AthleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Athlete::with('state', 'city');

        // Búsqueda
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filtros
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtro para mostrar solo activos (excluir inactivos)
        if ($request->has('show_inactive') && $request->show_inactive) {
            // Mostrar todos incluyendo inactivos
        } else {
            // Por defecto, mostrar solo activos y suspendidos
            $query->where('status', '!=', 'inactive');
        }

        if ($request->has('state_id') && $request->state_id) {
            $query->byState($request->state_id);
        }

        if ($request->has('team_name') && $request->team_name) {
            $query->byTeam($request->team_name);
        }

        // Ordenamiento
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Paginación
        $perPage = $request->get('per_page', 5);
        $athletes = $query->paginate($perPage);

        
        // Obtener datos para los filtros y selects
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $statuses = ['active', 'inactive', 'suspended'];
        $teams = Athlete::distinct()->pluck('team_name')->filter()->values();


        return view('admin.athletes.index', compact('athletes', 'states', 'cities', 'statuses', 'teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::orderBy('name')->get();
        $statuses = ['active' => 'Activo', 'inactive' => 'Inactivo', 'suspended' => 'Suspendido'];
        $genders = ['male' => 'Masculino', 'female' => 'Femenino', 'other' => 'Otro'];
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        return view('athletes.create', compact('states', 'statuses', 'genders', 'bloodTypes'));
    }


    public function store(Request $request){

        // Validación básica en backend (seguridad)
        $validated = $request->validate([
            'document' => 'required|string|max:20|unique:athletes,document',
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'required|date|before:-18 years',
            'gender' => 'required|in:masculino,femenino',
            'email' => 'required|email|max:150|unique:athletes,email',
            'status' => 'required|in:activo,inactivo,suspendido',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'phone' => 'nullable|string|max:20',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'team_name' => 'nullable|string|max:100',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ], [
            'birth_date.before' => 'El atleta debe ser mayor de 18 años.',
            'document.unique' => 'Este documento ya está registrado.',
            'email.unique' => 'Este email ya está registrado.',
        ]);
    
        try {
            $data = $request->except('photo');
    
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('athletes', $filename, 'public');
                $data['photo'] = $filename;
            }
    
            $athlete = Athlete::create($data);
    
            return redirect()->route('athletes.index')
                ->with('success', 'Atleta registrado exitosamente.');
    
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el atleta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id){
        $athlete = Athlete::with('state', 'city')->findOrFail($id);
        
        // Calcular edad
        $athlete->age = $athlete->birth_date ? \Carbon\Carbon::parse($athlete->birth_date)->age : null;
        
        // Formatear fecha de nacimiento
        $athlete->birth_date_formatted = $athlete->birth_date ? \Carbon\Carbon::parse($athlete->birth_date)->format('d/m/Y') : null;
        
        // Determinar si tiene foto
        $athlete->has_photo = $athlete->photo && file_exists(public_path('storage/athletes/' . $athlete->photo));
        
        return response()->json($athlete);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id){
        $athlete = Athlete::with('state', 'city')->findOrFail($id);
        
        // Agregar datos adicionales para la vista
        $athlete->age = $athlete->birth_date ? \Carbon\Carbon::parse($athlete->birth_date)->age : null;
        $athlete->birth_date_formatted = $athlete->birth_date ? \Carbon\Carbon::parse($athlete->birth_date)->format('Y-m-d') : null;
        $athlete->has_photo = $athlete->photo && file_exists(public_path('storage/athletes/' . $athlete->photo));
        $athlete->photo_url = $athlete->has_photo ? asset('storage/athletes/' . $athlete->photo) : null;
        
        return response()->json($athlete);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $athlete = Athlete::findOrFail($id);
        
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'document' => 'required|string|max:20|unique:athletes,document,' . $id,
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'required|date|before:-18 years',
            'gender' => 'required|in:masculino,femenino',
            'email' => 'required|email|max:150|unique:athletes,email,' . $id,
            'status' => 'required|in:activo,inactivo,suspendido',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'phone' => 'nullable|string|max:20',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'team_name' => 'nullable|string|max:100',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ], [
            'birth_date.before' => 'El atleta debe ser mayor de 18 años.',
            'document.unique' => 'Este documento ya está registrado.',
            'email.unique' => 'Este email ya está registrado.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except('photo', '_token', '_method');

            // Procesar la foto si se subió
            if ($request->hasFile('photo')) {
                // Eliminar foto anterior
                if ($athlete->photo && Storage::disk('public')->exists('athletes/' . $athlete->photo)) {
                    Storage::disk('public')->delete('athletes/' . $athlete->photo);
                }

                $file = $request->file('photo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('athletes', $filename, 'public');
                $data['photo'] = $filename;
            }

            $athlete->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Atleta actualizado exitosamente.',
                'athlete' => $athlete
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el atleta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Este método ahora implementa la lógica de "eliminación" con status
     */
    public function destroy(Athlete $athlete){

        try {
            // Verificar si el atleta puede ser "eliminado"
            if (!$athlete->canBeDeleted()) {
                // Si tiene registros futuros, solo lo desactivamos
                $athlete->update(['status' => 'inactive']);
                
                $message = $athlete->getDeletionErrorMessage() . ' Por lo tanto, solo se ha desactivado el perfil.';
                $type = 'warning';
            } else {
                // Si no tiene registros futuros, lo "eliminamos" completamente
                // Primero verificamos si tiene registros pasados
                if ($athlete->hasRegistrations()) {
                    // Tiene registros pasados, solo desactivamos
                    $athlete->update(['status' => 'inactive']);
                    $message = 'El atleta tiene registros históricos. Se ha desactivado el perfil pero los datos se mantienen.';
                    $type = 'warning';
                } else {
                    // No tiene ningún registro, podemos eliminarlo físicamente (opcional)
                    // O también solo desactivarlo
                    
                    // Opción 1: Solo desactivar (recomendado)
                    $athlete->update(['status' => 'inactive']);
                    $message = 'Atleta desactivado exitosamente.';
                    $type = 'success';
                    
                    // Opción 2: Eliminar físicamente (si quieres conservar el comportamiento original)
                    // if ($athlete->photo && Storage::disk('public')->exists('athletes/' . $athlete->photo)) {
                    //     Storage::disk('public')->delete('athletes/' . $athlete->photo);
                    // }
                    // $athlete->delete(); // Esto requeriría SoftDeletes
                }
            }
            
            return redirect()->route('athletes.index')
                ->with($type, $message);
                
        } catch (\Exception $e) {
            return redirect()->route('athletes.index')
                ->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Restaurar un atleta inactivo
     */
    public function restore($id){

        $athlete = Athlete::findOrFail($id);
        
        if ($athlete->status === 'inactive') {
            $athlete->update(['status' => 'active']);
            return redirect()->route('athletes.index')
                ->with('success', 'Atleta restaurado exitosamente.');
        }
        
        return redirect()->route('athletes.index')
            ->with('warning', 'Este atleta no está inactivo.');
    }

    /**
     * Método adicional: Eliminación forzada (solo para administradores)
     */
    public function forceDelete($id)
    {
        $athlete = Athlete::findOrFail($id);
        
        // Verificar si tiene registros
        if ($athlete->hasRegistrations()) {
            return redirect()->route('athletes.index')
                ->with('error', 'No se puede eliminar permanentemente un atleta con registros históricos.');
        }
        
        // Eliminar foto
        if ($athlete->photo && Storage::disk('public')->exists('athletes/' . $athlete->photo)) {
            Storage::disk('public')->delete('athletes/' . $athlete->photo);
        }
        
        $athlete->delete(); // Esto requeriría SoftDeletes en el modelo
        
        return redirect()->route('athletes.index')
            ->with('success', 'Atleta eliminado permanentemente.');
    }

    /**
     * Mostrar atletas inactivos (eliminados lógicamente)
     */
    public function trashed()
    {
        $athletes = Athlete::where('status', 'inactive')->paginate(15);
        
        return view('athletes.trashed', compact('athletes'));
    }
}