<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(){

        $orders = Order::with(['user', 'orderItems.product'])
                      ->latest()
                      ->paginate(10);
                      /* dd($orders->toArray()); */

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order){

        $order->load(['user', 'userAddress', 'orderItems.product']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order){

        $order->load(['user', 'userAddress', 'orderItems.product']);

        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order){

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
                         ->with('success', 'Orden actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order){

        $order->delete();

        return redirect()->route('orders.index')
                         ->with('success', 'Orden eliminada exitosamente.');
    }

    /**
     * Display user's orders
     */
    public function myOrders(){

        $orders = Order::with(['orderItems.product'])
                      ->where('user_id', Auth::id())
                      ->latest()
                      ->paginate(10);

        return view('orders.my-orders', compact('orders'));
    }

    /**
     * Update order status via AJAX
     */
    public function updateStatus(Request $request, Order $order){
        
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado exitosamente.',
            'status' => $order->status
        ]);
    }
    

    public function calculateShipping($shippingAddressId){

        $address = ShippingAddress::with(['zone.city'])->find($shippingAddressId);
        
        $shippingPrice = $address->shipping_price;
        $cityName = $address->city_name;
        $zoneName = $address->zone_name;
        
        return [
            'price' => $shippingPrice,
            'city' => $cityName,
            'zone' => $zoneName
        ];
    }


    public function createShippingAddress(Request $request){

        $request->validate([
            'address' => 'required|string',
            'zone_id' => 'required|exists:zones,id',
        ]);

        $address = ShippingAddress::create([
            'user_id' => auth()->id(),
            'address' => $request->address,
            'zone_id' => $request->zone_id,
        ]);

        // Obtener información completa
        $shippingCost = $address->shipping_price;
        $fullLocation = $address->full_location;
        
        return response()->json([
            'address' => $address,
            'shipping_cost' => $shippingCost,
            'location' => $fullLocation
        ]);
    }

    /**
     * Display detailed order information for web view
     */
    public function orderDetails(Order $order){

        // Cargar relaciones disponibles
        $order->load([
            'user',
            'userAddress', // Esta relación existe
            'orderItems.product', // Solo product, no category ni images si no existen
            'payment' // Esta relación existe
        ]);

        // Calcular estadísticas CON LOS CAMPOS EXISTENTES
        $statistics = [
            'total_products' => $order->orderItems->sum('quantity'),
            'unique_products' => $order->orderItems->count(),
            'subtotal' => $order->orderItems->sum('subtotal'), // Usando el campo subtotal de OrderItem
            'total' => $order->total_price // Usando el campo existente
        ];

        // Timeline de la orden (solo con campos que existen)
        $timeline = [];
        
        // Orden creada (created_at existe siempre)
        $timeline[] = [
            'date' => $order->created_at,
            'event' => 'Orden creada',
            'description' => 'La orden fue creada en el sistema',
            'icon' => 'bi-cart-plus'
        ];
        
        // Si hay un pago relacionado
        if ($order->payment) {
            $timeline[] = [
                'date' => $order->payment->created_at, // Usar created_at del pago
                'event' => 'Pago registrado',
                'description' => 'Se registró un pago para esta orden',
                'icon' => 'bi-credit-card'
            ];
        }
        
        // Evento basado en el status actual
        $statusEvents = [
            'pending' => ['Orden pendiente', 'La orden está en espera de procesamiento'],
            'paid' => ['Orden pagada', 'La orden ha sido pagada'],
            'shipped' => ['Orden enviada', 'La orden ha sido despachada'],
            'cancelled' => ['Orden cancelada', 'La orden fue cancelada']
        ];
        
        if (isset($statusEvents[$order->status])) {
            $timeline[] = [
                'date' => $order->updated_at, // Usar updated_at cuando cambió el status
                'event' => $statusEvents[$order->status][0],
                'description' => $statusEvents[$order->status][1],
                'icon' => $order->status == 'pending' ? 'bi-clock' : 
                         ($order->status == 'paid' ? 'bi-check-circle' : 
                         ($order->status == 'shipped' ? 'bi-truck' : 'bi-x-circle'))
            ];
        }

        return view('orders.details', compact('order', 'statistics', 'timeline'));
    }

    /**
     * Generate order timeline
     */
    private function getOrderTimeline(Order $order){
        $timeline = [];
        
        // Orden creada
        $timeline[] = [
            'date' => $order->created_at,
            'event' => 'Orden creada',
            'description' => 'La orden fue creada en el sistema',
            'icon' => 'bi-cart-plus'
        ];
        
        // Pago (si existe)
        if ($order->paid_at) {
            $timeline[] = [
                'date' => $order->paid_at,
                'event' => 'Pago confirmado',
                'description' => 'El pago fue procesado exitosamente',
                'icon' => 'bi-credit-card'
            ];
        }
        
        // Envío (si existe)
        if ($order->shipped_at) {
            $timeline[] = [
                'date' => $order->shipped_at,
                'event' => 'Orden enviada',
                'description' => 'La orden ha sido despachada',
                'icon' => 'bi-truck'
            ];
        }
        
        // Entrega (si existe)
        if ($order->delivered_at) {
            $timeline[] = [
                'date' => $order->delivered_at,
                'event' => 'Orden entregada',
                'description' => 'La orden fue entregada al cliente',
                'icon' => 'bi-house-check'
            ];
        }
        
        // Ordenar por fecha
        usort($timeline, function($a, $b) {
            return $a['date'] <=> $b['date'];
        });
        
        return $timeline;
    }

     /**
     * Display order dispatch information
     */
    public function ordedispatched(Order $order){
        
        // Cargar todas las relaciones necesarias para el despacho
        $order->load([
            'user',
            'userAddress.zone.city', // Para dirección completa
            'orderItems.product', // Para productos
            'orderItems.product.images', // Para imágenes de productos
            'payment', // Información de pago
            'shippingAddress' // Dirección de envío específica
        ]);

        // Calcular totales
        $totals = [
            'subtotal' => $order->orderItems->sum('subtotal'),
            'shipping' => $order->shipping_cost ?? 0,
            'tax' => $order->tax_amount ?? 0,
            'discount' => $order->discount_amount ?? 0,
            'total' => $order->total_price
        ];

        // Obtener información de despacho
        $dispatchInfo = [
            'dispatch_date' => now(),
            'dispatch_number' => 'DESP-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'prepared_by' => Auth::user()->name,
            'status' => $order->status
        ];

        return view('dispatched.ordedispatched', compact('order', 'totals', 'dispatchInfo'));
    }
}
