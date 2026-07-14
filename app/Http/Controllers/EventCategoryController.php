<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EventCategoryController extends Controller
{
    
    public function index(Request $request){

        Log::info('=== INICIO INDEX CATEGORÍAS ===');
        
        $events = Event::where('status', 'published')->get();
        $event_categories = EventCategory::with('event')->paginate(5);
        
        Log::info('Total de categorías: ' . $event_categories->total());
        Log::info('Categorías en esta página: ' . $event_categories->count());
        
        // Corregir: Si estamos en una página sin registros pero hay registros totales
        if ($event_categories->isEmpty() && $event_categories->total() > 0) {
            Log::info('Redirigiendo a la primera página');
            return redirect()->route('event_categories.index');
        }
        
        return view('admin.event_categories.index', compact('events', 'event_categories'));
    }

    
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        Log::info('=== INICIO STORE CATEGORÍA ===');
        Log::info('Datos recibidos:', $request->all());

        try {
            // Validación simple - SIN validación de duplicado
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:150',
                'event_id' => 'required|exists:events,id',
                'min_age' => 'nullable|integer|min:0|max:99',
                'max_age' => 'nullable|integer|min:0|max:99',
                'gender_restriction' => 'nullable|in:MASCULINO,FEMENINO',
                'status' => 'boolean'
            ]);

            // Validar que min_age no sea mayor que max_age
            $validator->after(function ($validator) use ($request) {
                if ($request->min_age && $request->max_age) {
                    if ($request->min_age > $request->max_age) {
                        $validator->errors()->add('min_age', 'La edad mínima no puede ser mayor que la edad máxima');
                    }
                }
            });

            if ($validator->fails()) {
                Log::warning('Error de validación:', $validator->errors()->toArray());
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors(),
                        'message' => 'Error de validación'
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Crear la categoría
            $category = EventCategory::create([
                'name' => strtoupper($request->name),
                'event_id' => $request->event_id,
                'min_age' => $request->min_age,
                'max_age' => $request->max_age,
                'gender_restriction' => $request->gender_restriction,
                'status' => $request->status ?? 1
            ]);

            Log::info('Categoría creada exitosamente! ID: ' . $category->id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoría creada exitosamente',
                    'data' => $category->load('event')
                ]);
            }

            return redirect()->route('event_categories.index')
                ->with('success', 'Categoría creada exitosamente');

        } catch (\Exception $e) {
            Log::error('ERROR GENERAL EN STORE CATEGORÍA:', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'línea' => $e->getLine(),
                'traza' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la categoría: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al crear la categoría: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id){
        Log::info('=== INICIO EDIT CATEGORÍA ===');
        Log::info('ID a editar: ' . $id);
        
        try {
            $category = EventCategory::with('event')->findOrFail($id);
            $events = Event::where('status', 'published')->get();
            
            Log::info('Categoría encontrada:', $category->toArray());
            
            // Retornar la vista con los datos
            return view('admin.event_categories.index', compact('category', 'events'));
            
        } catch (\Exception $e) {
            Log::error('Error en edit:', [
                'mensaje' => $e->getMessage(),
                'id' => $id
            ]);
            
            return redirect()->route('event_categories.index')
                ->with('error', 'Categoría no encontrada');
        }
    }

   
    /**
 * Update the specified resource in storage.
 */
public function update(Request $request, $id)
{
    Log::info('=== INICIO UPDATE CATEGORÍA ===');
    Log::info('ID:', ['id' => $id]);
    Log::info('Datos recibidos:', $request->all());

    try {
        $category = EventCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'event_id' => 'required|exists:events,id',
            'min_age' => 'nullable|integer|min:0|max:99',
            'max_age' => 'nullable|integer|min:0|max:99',
            'gender_restriction' => 'nullable|in:MASCULINO,FEMENINO',
            'status' => 'boolean'
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->min_age && $request->max_age) {
                if ($request->min_age > $request->max_age) {
                    $validator->errors()->add('min_age', 'La edad mínima no puede ser mayor que la edad máxima');
                }
            }
        });

        if ($validator->fails()) {
            Log::warning('Error de validación:', $validator->errors()->toArray());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación'
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => strtoupper($request->name),
            'event_id' => $request->event_id,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
            'gender_restriction' => $request->gender_restriction,
            'status' => $request->status ?? 1
        ]);

        Log::info('Categoría actualizada exitosamente! ID: ' . $category->id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente',
                'data' => $category->load('event')
            ]);
        }

        return redirect()->route('event_categories.index')
            ->with('success', 'Categoría actualizada exitosamente');

    } catch (\Exception $e) {
        Log::error('ERROR GENERAL EN UPDATE CATEGORÍA:', [
            'mensaje' => $e->getMessage(),
            'archivo' => $e->getFile(),
            'línea' => $e->getLine()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la categoría: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Error al actualizar la categoría: ' . $e->getMessage())
            ->withInput();
    }
}

   
    public function show(string $id)
    {
        //
    }

    
    /* En espera, para eliminar una categoría deportiva, se debe validar que no tenga eventos
       oh inscripciones  asociadas */
    public function destroy(string $id)
    {
        //
    }
}
