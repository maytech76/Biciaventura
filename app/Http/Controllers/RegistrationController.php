<?php
// app/Http/Controllers/RegistrationController.php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator; // ✅ Agregar esta línea
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    public function index(){

        $registrations = Registration::with(['athlete', 'event', 'category'])->paginate(10);
        $athletes = Athlete::where('status', 'activo')->get();
        $events = Event::where('status', 'published')
                       ->where('event_date', '>=', now())
                       ->orderBy('event_date', 'asc')
                       ->get();
        
        return view('admin.registrations.index', compact('registrations', 'athletes', 'events'));
    }

  
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'athlete_id' => 'required|exists:athletes,id',
                'event_id' => 'required|exists:events,id',
                'event_category_id' => 'required|exists:event_categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'payment_method' => 'nullable|in:efectivo,transferencia,tarjeta,otros',
                'payment_reference' => 'nullable|string|max:100',
                'amount' => 'nullable|numeric|min:0',
                'status' => 'required|in:inscrito,pendiente,confirmado,retirado',
                'notes' => 'nullable|string'
            ]);
    
            // Obtener modelos relacionados
            $athlete = Athlete::find($request->athlete_id);
            $event = Event::find($request->event_id);
            $category = EventCategory::find($request->event_category_id);
    
            if (!$athlete || !$event || !$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos: Atleta, Evento o Categoría no encontrados.'
                ], 422);
            }
    
            // Verificar elegibilidad del atleta
            $eligibility = $category->isAthleteEligible($athlete, $event->event_date);
            
            if (!$eligibility['eligible']) {
                return response()->json([
                    'success' => false,
                    'message' => $eligibility['message']
                ], 422);
            }
    
            // Verificar que no exista una inscripción duplicada
            $existingRegistration = Registration::where('athlete_id', $request->athlete_id)
                                                ->where('event_id', $request->event_id)
                                                ->where('event_category_id', $request->event_category_id)
                                                ->first();
    
            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este atleta ya está inscrito en esta categoría para este evento.'
                ], 422);
            }
    
            // Generar código único usando el nombre de la categoría
            $code = Registration::generateCode($category->name, $request->event_id);
    
            // Procesar imagen
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('registrations', $imageName, 'public');
            }
    
            // Crear inscripción
            $registration = Registration::create([
                'athlete_id' => $request->athlete_id,
                'event_id' => $request->event_id,
                'event_category_id' => $request->event_category_id,
                'code' => $code,
                'image' => $imagePath,
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'amount' => $request->amount,
                'status' => $request->status,
                'notes' => $request->notes,
                'confirmed_at' => $request->status === 'confirmado' ? now() : null,
                'cancelled_at' => $request->status === 'retirado' ? now() : null,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => '✅ Inscripción creada exitosamente',
                'registration' => $registration,
                'code' => $code
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error al crear inscripción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $registration = Registration::with(['athlete', 'event', 'category'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'registration' => $registration
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id){
        
        try {
            $registration = Registration::with(['athlete', 'event', 'category'])->findOrFail($id);
            
            // Agregar datos adicionales
            $registration->athlete->full_name = $registration->athlete->name . ' ' . $registration->athlete->last_name;
            $registration->athlete->age = $registration->athlete->birth_date ? \Carbon\Carbon::parse($registration->athlete->birth_date)->age : null;
            
            return response()->json([
                'success' => true,
                'registration' => $registration
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        
        try {
            $registration = Registration::findOrFail($id);
            
            // Validar los datos
            $validator = Validator::make($request->all(), [
                'athlete_id' => 'required|exists:athletes,id',
                'event_id' => 'required|exists:events,id',
                'event_category_id' => 'required|exists:event_categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'payment_method' => 'nullable|in:efectivo,transferencia,tarjeta,otros',
                'payment_reference' => 'nullable|string|max:100',
                'amount' => 'nullable|numeric|min:0',
                'status' => 'required|in:inscrito,pendiente,confirmado,retirado',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->except('image');

            // Procesar imagen si se subió
            if ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($registration->image && Storage::disk('public')->exists($registration->image)) {
                    Storage::disk('public')->delete($registration->image);
                }

                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('registrations', $filename, 'public');
                $data['image'] = $path;
            }

            // Si el estado cambia a confirmado, registrar fecha
            if ($request->status === 'confirmado' && $registration->status !== 'confirmado') {
                $data['confirmed_at'] = now();
            }

            // Si el estado cambia a retirado, registrar fecha
            if ($request->status === 'retirado' && $registration->status !== 'retirado') {
                $data['cancelled_at'] = now();
            }

            $registration->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Inscripción actualizada exitosamente.',
                'registration' => $registration
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $registration = Registration::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($registration->image && Storage::disk('public')->exists($registration->image)) {
            Storage::disk('public')->delete($registration->image);
        }
        
        $registration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inscripción eliminada exitosamente'
        ]);
    }

    public function checkEligibility(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|exists:athletes,id',
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:event_categories,id'
        ]);

        $athlete = Athlete::find($request->athlete_id);
        $event = Event::find($request->event_id);
        $category = EventCategory::find($request->category_id);

        $eligibility = $category->isAthleteEligible($athlete, $event->event_date);

        return response()->json([
            'success' => true,
            'eligibility' => $eligibility
        ]);
    }

    
    
    public function getCategoriesByEvent(Request $request, $eventId)
    {
        try {
            // Verificar que el evento existe
            $event = Event::find($eventId);
            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evento no encontrado'
                ], 404);
            }
    
            // Obtener el ID del atleta si se envió
            $athleteId = $request->input('athlete_id');
            $athlete = null;
            
            if ($athleteId) {
                $athlete = Athlete::find($athleteId);
            }
    
            // Obtener todas las categorías activas del evento
            $categories = EventCategory::where('event_id', $eventId)
                                       ->where('status', 1)
                                       ->orderBy('name', 'asc')
                                       ->get();
    
            // Si hay un atleta seleccionado, filtrar categorías por elegibilidad
            $formattedCategories = [];
            $hasEligibleCategories = false;
    
            foreach ($categories as $category) {
                if ($athlete) {
                    // Verificar elegibilidad del atleta para esta categoría
                    $eligibility = $category->isAthleteEligible($athlete, $event->event_date);
                    
                    if ($eligibility['eligible']) {
                        $formattedCategories[] = [
                            'id' => $category->id,
                            'name' => $category->name,
                            'min_age' => $category->min_age,
                            'max_age' => $category->max_age,
                            'gender_restriction' => $category->gender_restriction,
                            'label' => $this->formatCategoryLabel($category),
                            'eligible' => true
                        ];
                        $hasEligibleCategories = true;
                    }
                    // Solo mostramos las categorías elegibles, ignoramos las no elegibles
                } else {
                    // Sin atleta seleccionado, mostrar todas las categorías
                    $formattedCategories[] = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'min_age' => $category->min_age,
                        'max_age' => $category->max_age,
                        'gender_restriction' => $category->gender_restriction,
                        'label' => $this->formatCategoryLabel($category),
                        'eligible' => true
                    ];
                    $hasEligibleCategories = true;
                }
            }
    
            return response()->json([
                'success' => true,
                'categories' => $formattedCategories,
                'has_eligible' => $hasEligibleCategories,
                'total_categories' => $categories->count(),
                'has_athlete' => $athlete ? true : false,
                'athlete_info' => $athlete ? [
                    'full_name' => $athlete->full_name,
                    'age' => $athlete->age,
                    'gender' => $athlete->gender
                ] : null
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar categorías: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function formatCategoryLabel($category)
    {
        $label = $category->name;
        
        if ($category->min_age !== null && $category->max_age !== null) {
            $label .= " ({$category->min_age}-{$category->max_age} años)";
        } elseif ($category->min_age !== null) {
            $label .= " (mín {$category->min_age} años)";
        } elseif ($category->max_age !== null) {
            $label .= " (máx {$category->max_age} años)";
        }
        
        if ($category->gender_restriction) {
            $genderMap = [
                'femenino' => '👩 Femenino',
                'masculino' => '👨 Masculino'
            ];
            $label .= " - " . ($genderMap[$category->gender_restriction] ?? $category->gender_restriction);
        }
        
        return $label;
    }

    /**
     * Genera un código único para la inscripción usando el método del modelo
     */
    private function generateUniqueCode($categoryName, $eventId)
    {
        return Registration::generateCode($categoryName, $eventId);
    }
}