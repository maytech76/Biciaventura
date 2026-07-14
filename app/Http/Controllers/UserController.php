<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;




class UserController extends Controller
{

    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request){

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended('/dashboard')
                             ->with('success', '¡Bienvenido de nuevo!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('login');
    }

    /**
     * Procesar el registro
     */
    public function register(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'customer'; // Asignar rol por defecto

        $user = User::create($validated);

        Auth::login($user);

        return redirect('/dashboard')
               ->with('success', '¡Cuenta creada exitosamente! Bienvenido a Maydev.');
    }

    



    public function index(){

        $users = User::withCount('orders')
                    ->latest()
                    ->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,customer',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuario creado exitosamente.');
    }



    public function update(Request $request, User $user){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,customer',
            'address' => 'nullable|string|max:500',
        ]);

        // Actualizar campos individualmente si hay problemas
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = $validated['role'];
        $user->address = $validated['address'] ?? null;

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user){

        // Verificar si el usuario tiene órdenes antes de eliminar
        if ($user->orders()->count() > 0) {
            return redirect()->route('users.index')
                             ->with('error', 'No se puede eliminar el usuario porque tiene órdenes asociadas.');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Usuario eliminado exitosamente.');
    }



    public function dashboard(){
        $user = auth()->user();
        $userId = $user->id;

        $totalOrdenes = Order::where('user_id', $userId)->count();
        $totalPendientes = Cart::where('user_id', $userId)->count();
        $totalWishlist = Wishlist::where('user_id', $userId)->count();

        $orders = Order::with(['orderItems.product', 'shippingAddress', 'payment'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('dashboard', compact('user', 'totalOrdenes', 'totalPendientes', 'totalWishlist', 'orders'));
    }


        /**
     * Actualizar el perfil del usuario autenticado - Versión robusta
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request){

        try {
            // Obtener ID del usuario autenticado
            $userId = auth()->id();
            
            if (!$userId) {
                return redirect()->route('login')
                                ->with('error', 'Sesión expirada. Por favor, inicie sesión nuevamente.');
            }

            // Buscar usuario directamente desde la base de datos
            $user = User::find($userId);
            
            if (!$user) {
                return redirect()->route('login')
                                ->with('error', 'Usuario no encontrado.');
            }

            // Reglas de validación
            $validationRules = [
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'password' => ['nullable', 'confirmed', Password::defaults()],
            ];

            if ($request->filled('password')) {
                $validationRules['current_password'] = ['required', 'current_password'];
            }

            $validated = $request->validate($validationRules);

            // Actualizar campos individualmente
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? $user->phone;
            $user->address = $validated['address'] ?? $user->address;

            if (isset($validated['password']) && $validated['password']) {
                $user->password = Hash::make($validated['password']);
            }

            // Guardar cambios
            $user->save();

            return redirect()->route('users.profile')
                            ->with('success', 'Perfil actualizado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Laravel maneja automáticamente esta excepción
        } catch (\Exception $e) {
            Log::error('Error crítico al actualizar perfil: ' . $e->getMessage(), [
                'user_id' => $userId ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('users.profile')
                            ->with('error', 'Error del sistema: ' . $e->getMessage());
        }
    }

    /* Cerrar Sesion de Usuario */
    public function logout(Request $request){

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Has cerrado sesión exitosamente.');
    }

}
