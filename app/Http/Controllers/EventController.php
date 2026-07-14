<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){

        $states = State::all();
        $cities = City::all();
        $events = Event::all();
        return view('admin.events.index', compact('events', 'states', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){

        $states = State::where('status', 1)->get();
        $cities = City::where('status', 1)->get();
        $events = Event::with(['user', 'city'])->paginate(15);
        $statusOptions = Event::getStatusOptions(); // Para usar en la vista
        
        return view('admin.events.create', compact('events', 'states', 'cities', 'statusOptions'));
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){

        // Log de inicio
        Log::info('=== INICIO STORE EVENTO ===');
        Log::info('Usuario autenticado ID: ' . auth()->id());
        Log::info('Datos recibidos:', $request->all());

        try {
            // Log de validación
            Log::info('Iniciando validación de datos...');
            
            // Validación
            $validated = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'required|string|max:250',
                'type' => 'required|in:mtb,route,downhill,enduro,sport',
                'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'address' => 'required|string|max:250',
                'event_date' => 'required|date',
                'registration_deadline' => 'required|date|after:now|before:event_date',
                'phone' => 'required|string|max:20',
                'name_manager' => 'required|string|max:150',
                'email_manager' => 'required|email|max:150',
                'status' => 'required|in:draft,published,completed,cancelled',
            ]);

            Log::info('Validación exitosa. Datos validados:', $validated);

            // Procesar banner
            Log::info('Procesando banner...');
            if ($request->hasFile('banner')) {
                Log::info('Archivo banner detectado:', [
                    'nombre' => $request->file('banner')->getClientOriginalName(),
                    'tamaño' => $request->file('banner')->getSize(),
                    'mime' => $request->file('banner')->getMimeType()
                ]);
                
                $bannerPath = $request->file('banner')->store('events/banners', 'public');
                $validated['banner'] = $bannerPath;
                
                Log::info('Banner guardado en: ' . $bannerPath);
            } else {
                Log::warning('No se encontró archivo banner en la solicitud');
            }

            // Asignar user_id
            $validated['user_id'] = auth()->id();
            Log::info('Asignando user_id: ' . $validated['user_id']);

            // Datos finales a insertar
            Log::info('Datos finales a insertar:', $validated);

            // Crear el evento
            Log::info('Intentando crear el evento...');
            $event = Event::create($validated);
            
            Log::info('Evento creado exitosamente! ID: ' . $event->id);
            Log::info('Datos del evento creado:', $event->toArray());
            
            // Redirigir al índice con éxito
            Log::info('Redirigiendo a events.index');
            return redirect()->route('events.index')
                ->with('create_success', '¡Evento creado exitosamente!')
                ->with('event_name', $event->name) // Para mostrar el nombre en el SweetAlert
                ->with('show_alert', true); // Flag para mostrar el SweetAlert
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación específicamente
            Log::error('ERROR DE VALIDACIÓN:', [
                'errores' => $e->errors(),
                'datos_recibidos' => $request->all()
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor, corrige los errores del formulario');
                
        } catch (\Exception $e) {
            // Capturar cualquier otro error
            Log::error('ERROR GENERAL EN STORE:', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'línea' => $e->getLine(),
                'traza' => $e->getTraceAsString(),
                'datos_recibidos' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al crear el evento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        
        try {
            $event = Event::with(['user', 'state', 'city'])->findOrFail($id);
            
            // Verificar si es una petición AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $event
                ]);
            }
            
            return view('admin.events.show', compact('event'));
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evento no encontrado'
                ], 404);
            }
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){

        try {
            $event = Event::with(['state', 'city'])->findOrFail($id);
            $states = State::where('status', 1)->get();
            $cities = City::where('status', 1)->get();
            $statusOptions = Event::getStatusOptions();
            
            return view('admin.events.edit', compact('event', 'states', 'cities', 'statusOptions'));
            
        } catch (\Exception $e) {
            Log::error('Error en edit: ' . $e->getMessage());
            return redirect()->route('events.index')
                ->with('error', 'Evento no encontrado');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){

        Log::info('=== INICIO UPDATE EVENTO ===');
        Log::info('ID del evento: ' . $id);
        Log::info('Datos recibidos:', $request->all());

        try {
            $event = Event::findOrFail($id);
            
            // Validación
            $validated = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'required|string|max:250',
                'type' => 'required|in:mtb,route,downhill,enduro,sport',
                'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'address' => 'required|string|max:250',
                'event_date' => 'required|date',
                'registration_deadline' => 'required|date|before:event_date',
                'phone' => 'required|string|max:20',
                'name_manager' => 'required|string|max:150',
                'email_manager' => 'required|email|max:150',
                'status' => 'required|in:draft,published,completed,cancelled',
            ]);

            Log::info('Validación exitosa:', $validated);

            // Procesar banner si se sube uno nuevo
            if ($request->hasFile('banner')) {
                Log::info('Procesando nuevo banner...');
                
                // Eliminar banner antiguo
                if ($event->banner && Storage::disk('public')->exists($event->banner)) {
                    Storage::disk('public')->delete($event->banner);
                    Log::info('Banner antiguo eliminado: ' . $event->banner);
                }
                
                $bannerPath = $request->file('banner')->store('events/banners', 'public');
                $validated['banner'] = $bannerPath;
                Log::info('Nuevo banner guardado: ' . $bannerPath);
            } else {
                // Mantener el banner existente
                $validated['banner'] = $event->banner;
            }

            // Actualizar el evento
            Log::info('Actualizando evento...');
            $event->update($validated);
            
            Log::info('Evento actualizado exitosamente! ID: ' . $event->id);
            
            return redirect()->route('events.index')
                ->with('update_success', '¡Evento actualizado exitosamente!')
                ->with('event_name', $event->name)
                ->with('show_alert', true);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('ERROR DE VALIDACIÓN EN UPDATE:', [
                'errores' => $e->errors(),
                'datos_recibidos' => $request->all()
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor, corrige los errores del formulario');
                
        } catch (\Exception $e) {
            Log::error('ERROR GENERAL EN UPDATE:', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'línea' => $e->getLine(),
                'traza' => $e->getTraceAsString(),
                'datos_recibidos' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al actualizar el evento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){

        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado correctamente');
    }

    /**
     * Vista de calendario de eventos
     */
    public function calendar(){
        
        return view('admin.events.calendar');
    }

    /**
     * Eventos próximos
     */
    public function upcoming()
    {
        $events = Event::where('event_date', '>=', now())
            ->where('status', 'published')
            ->orderBy('event_date')
            ->get();
        return response()->json($events);
    }

    /**
     * Publicar evento
     */
    public function publish(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'published';
        $event->save();
        return redirect()->back()->with('success', 'Evento publicado correctamente');
    }

    /**
     * Cancelar evento
     */
    public function cancel(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'cancelled';
        $event->save();
        return redirect()->back()->with('success', 'Evento cancelado correctamente');
    }
}