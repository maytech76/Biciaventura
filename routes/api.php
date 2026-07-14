<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DolarController;



// ============================================
// RUTAS PÚBLICAS (sin autenticación)
// ============================================

// Autenticación pública
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Productos públicos
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']); // Agregar si existe

// Categorías públicas
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

//Tasa de cambio por Dolar
Route::get('/dolar/rate', [DolarController::class, 'getCurrentRate']);
Route::get('/dolar/historial', [DolarController::class, 'getHistorial']);
Route::post('/dolar/force-update', [DolarController::class, 'forceUpdate']);


// ============================================
// RUTAS PROTEGIDAS (requieren autenticación con Sanctum)
// ============================================

Route::middleware('auth:sanctum')->group(function(){
    
    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Usuario - rutas adicionales
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user/profile', [UserController::class, 'getUser']); // Cambiado a /user/profile
    
    // Productos protegidos
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']); // Agregar si existe
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Agregar si existe
    
    // Categorías protegidas
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::post('/categories/{id}/photo', [CategoryController::class, 'updatePhoto']); // NUEVA
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});