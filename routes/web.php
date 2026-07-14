<?php

use App\Http\Controllers\AthleteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;

//Cuentas Por Cobrar
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\OldPaymentController;

//Dolar tasa
use App\Http\Controllers\DolarController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\OrderServiceController;
use App\Http\Controllers\VehicleController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas PÚBLICAS (sin autenticación)
Route::get('/shop', [ProductController::class, 'shop'])->name('shop.index');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::resource('/products', ProductController::class); // publico
Route::get('/product/{id}/{slug}', [ProductController::class, 'details'])->name('product.details'); // Cambiado de /{id}/{slug}

// Rutas de autenticación
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);

// Rutas para tasas de cambio
Route::get('/tasas-cambio', [DolarController::class, 'index'])->name('dolar.index');

    // Rutas para reset de contraseña
Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [UserController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.update');


    Route::middleware('auth')->group(function () {
    // Rutas de ADMIN
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        Route::resource('/products', ProductController::class); // Ahora es /admin/products
        
        /* cambiar imagen principal (opcional) */
       
        Route::resource('/categories', CategoryController::class);
        Route::resource('/cities', CityController::class);

        // Ruta para obtener detalles de una ciudad específica
        Route::get('/cities/{id}/details', [CityController::class, 'getCityDetails'])->name('cities.details');

        // Ruta para obtener ciudades por estado
        Route::get('/cities-by-state/{stateId}', [EventController::class, 'getCitiesByState'])->name('cities.by-state');


        // Rutas para administradores
        Route::resource('orders', OrderController::class)->except(['create', 'store']);
        Route::get('/orders/{order}/details', [OrderController::class, 'orderdetails'])->name('orders.orderdetails');
        Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
      
        //Rutas para el modulo de Categorias deportivas
        Route::resource('event_categories', EventCategoryController::class);

        // Grupo de rutas para athletes
        Route::resource('athletes', AthleteController::class);

        // Rutas adicionales para gestión de estado
        Route::prefix('athletes')->name('athletes.')->group(function () {
            
            // Restaurar atleta inactivo
            Route::patch('restore/{id}', [AthleteController::class, 'restore'])->name('restore');
            
            // Eliminación forzada (solo admin)
            Route::delete('force-delete/{id}', [AthleteController::class, 'forceDelete'])->name('force-delete');
            
            // Ver atletas inactivos
            Route::get('trashed', [AthleteController::class, 'trashed'])->name('trashed');
            
            // Cambiar estado manualmente (opcional)
            Route::patch('change-status/{id}', [AthleteController::class, 'changeStatus'])->name('change-status');
        });

        // Ruta para verificar duplicados de categorías
        Route::get('/check-category-duplicate', [EventCategoryController::class, 'checkDuplicate'])->name('categories.check-duplicate');

        Route::resource('users', UserController::class);

        Route:: resource('companies', CompanyController::class);


        /* Modulo de Eventos */
        Route::resource('events', EventController::class);
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
        Route::get('events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
        Route::get('events/upcoming', [EventController::class, 'upcoming'])->name('events.upcoming');
        Route::post('events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
        Route::post('events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');

        /* Modulo Inscripciones de atletas a eventos */
        Route::resource('registrations', RegistrationController::class);
        // En routes/web.php
        Route::get('/registrations/{id}/show-json', [RegistrationController::class, 'show'])->name('registrations.show');

        // Funcion editar registro selecionado
        Route::get('/registrations/{id}/edit', [RegistrationController::class, 'edit'])->name('registrations.edit');
        Route::put('/registrations/{id}', [RegistrationController::class, 'update'])->name('registrations.update');
    
      
        // Rutas adicionales para AJAX - DEBEN ESTAR DESPUÉS DE resource
        Route::get('/events/{eventId}/categories', [RegistrationController::class, 'getCategoriesByEvent'])->name('events.categories');
        Route::post('/registrations/check-eligibility', [RegistrationController::class, 'checkEligibility'])->name('registrations.check-eligibility');
        Route::get('/registrations/{id}/show-json', [RegistrationController::class, 'show'])->name('registrations.show-json');


        /* Ruta para el componente Customers */
        Route::resource('customers', CustomerController::class);

         /* Ruta para el componente Vehicles */
        Route::resource('vehicles', VehicleController::class);

         /* Ruta para el componente Mechanics */
        Route::resource('/mechanics', MechanicController::class);
        Route::patch('mechanics/{id}/toggle-status', [MechanicController::class, 'toggleStatus'])->name('mechanics.toggle-status');
        Route::patch('mechanics/{id}/toggle-status', [MechanicController::class, 'toggleStatus'])->name('mechanics.toggle-status');

        
        /* Rutas para el módulo de órdenes de servicio*/
        Route::resource('order-services', OrderServiceController::class);

        /* Ruta para consulta de vehículos por usuario (para el módulo de órdenes de servicio) */
        Route::get('/get-vehicles-by-user/{userId}', [OrderServiceController::class, 'getVehiclesByUser'])->name('get.vehicles.by.user');
       
        /* Ruta para consulta de información del usuario (para el módulo de órdenes de servicio) */
        Route::get('/get-user-info/{userId}', [OrderServiceController::class, 'getUserInfo'])->name('get.user.info');

        /* Ruta para consulta de precio del producto (para el módulo de órdenes de servicio) */
        Route::get('/get-product-price/{productId}', [OrderServiceController::class, 'getProductPrice'])->name('get.product.price');
    });

   

    // Ruta para logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Carrito y Wishlist
    Route::post('/sync/cart-wishlist', [CartController::class, 'syncCartWishlist'])->name('sync.cart.wishlist'); // Coma agregada
    Route::delete('/cart/{product_id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/wishlist/{product_id}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Checkout y pagos
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/shipping-address', [ShippingAddressController::class, 'store'])->name('shipping.store');
    Route::post('/checkout/stripe', [PaymentController::class, 'checkout'])->name('stripe.checkout');
    Route::get('/checkout/success', [PaymentController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [PaymentController::class, 'cancel'])->name('checkout.cancel');

    // Dashboard usuario
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Direcciones de envío
    Route::post('/dashboard/address', [ShippingAddressController::class, 'storeAddress'])->name('address.store');
    Route::put('/dashboard/address/{id}', [ShippingAddressController::class, 'updateAddress'])->name('address.update');
    Route::delete('/dashboard/address/{id}', [ShippingAddressController::class, 'destroyAddress'])->name('address.destroy');

    // Actualizar perfil
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Rutas para administradores
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');

    //Rutas para el modulo Cuentas x Cobrar 
    Route::middleware(['auth', 'role:admin'])->group(function () {
        
        // Rutas para Cuentas por Cobrar
        Route::get('/receivables', [ReceivableController::class, 'index'])->name('receivables.index');
        Route::get('/receivables/create', [ReceivableController::class, 'create'])->name('receivables.create');
        Route::post('/receivables', [ReceivableController::class, 'store'])->name('receivables.store');
        Route::get('/receivables/{receivable}', [ReceivableController::class, 'show'])->name('receivables.show');
        Route::get('/receivables/{receivable}/edit', [ReceivableController::class, 'edit'])->name('receivables.edit');
        Route::put('/receivables/{receivable}', [ReceivableController::class, 'update'])->name('receivables.update');
        Route::delete('/receivables/{receivable}', [ReceivableController::class, 'destroy'])->name('receivables.destroy');
        
        // Rutas para Pagos
        Route::get('/receivables/{receivable}/payment', [ReceivableController::class, 'createPayment'])->name('receivables.payment.create');
        Route::post('/receivables/{receivable}/payments', [OldPaymentController::class, 'store'])->name('receivables.payments.store');
        Route::get('/payments/{payment}/receipt', [OldPaymentController::class, 'showReceipt'])->name('payments.receipt');
        Route::get('/payments/{payment}/receiptweb', [OldPaymentController::class, 'showReceipt'])->name('payments.receiptweb');
        Route::delete('/payments/{payment}', [OldPaymentController::class, 'destroy'])->name('payments.destroy');
    });


    //Vista para Formulario de despacho
    Route::get('/dispatched/{order}', [OrderController::class, 'ordedispatched'])->name('dispatched.ordedispatched');

   

});



/* require __DIR__.'/auth.php'; */