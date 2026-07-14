@extends('admin.layouts.master')

@section('title', 'Detalles de Orden #' . $order->id)

@section('content')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">
            <!-- tracking table start -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="title-header title-header-block package-card">
                                    <div>
                                        <h5>Orden #{{ $order->id }}</h5>
                                    </div>
                                    <div class="card-order-section">
                                        <ul>
                                            <li>{{ $order->created_at->format('F d, Y \a\t h:i a') }}</li>
                                            <li>{{ $statistics['total_products'] }} items</li>
                                            <li>Total ${{ number_format($statistics['total'], 2) }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="bg-inner cart-section order-details-table">
                                    <div class="row g-4">
                                        <div class="col-xl-8">
                                            <div class="table-responsive table-details">
                                                <table class="table cart-table table-borderless">
                                                    <thead>
                                                        <tr class="rouden">
                                                            <th>Producto</th>
                                                            <th class="text-center">Cantidad</th>
                                                            <th class="text-end">Precio Unitario</th>
                                                            <th class="text-end">Subtotal</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($order->orderItems as $item)
                                                        @php
                                                            $product = $item->product;
                                                        @endphp
                                                        <tr class="table-order">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="table-image me-3">
                                                                        @if($product && $product->main_image_url)
                                                                            <img src="{{ $product->main_image_url }}" 
                                                                                class="rounded-circle shadow" 
                                                                                width="60"
                                                                                alt="{{ $product->name }}">
                                                                        @else
                                                                            <div class="placeholder-img rounded-circle" style="width: 60px; height: 60px;">
                                                                                <i class="ri-image-line" style="font-size: 24px; color: #ccc;"></i>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div>
                                                                        {{-- <p class="mb-1 text-muted">Nombre del Producto</p> --}}
                                                                        <h6 class="mb-0">{{ $product->name ?? 'Producto no disponible' }}</h6>
                                                                        @if($product && $product->sku)
                                                                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <p class="mb-1 text-muted">Cantidad</p>
                                                                <h5 class="mb-0">{{ $item->quantity }}</h5>
                                                            </td>
                                                            <td class="text-end">
                                                               {{--  <p class="mb-1 text-muted">Precio Unitario</p> --}}
                                                                <h5 class="mb-0">${{ number_format($item->price, 2) }}</h5>
                                                            </td>
                                                            <td class="text-end">
                                                                {{-- <p class="mb-1 text-muted">Subtotal</p> --}}
                                                                <h5 class="mb-0">${{ number_format($item->subtotal, 2) }}</h5>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>

                                                    <tfoot>
                                                        <tr class="table-order">
                                                            <td colspan="2">
                                                                <h5>Subtotal :</h5>
                                                            </td>
                                                            <td class="text-end" colspan="2">
                                                                <h4>${{ number_format($statistics['subtotal'], 2) }}</h4>
                                                            </td>
                                                        </tr>

                                                        @if(($order->discount_amount ?? 0) > 0)
                                                        <tr class="table-order">
                                                            <td colspan="2">
                                                                <h5>Descuento :</h5>
                                                            </td>
                                                            <td class="text-end" colspan="2">
                                                                <h4 class="text-success">-${{ number_format($order->discount_amount ?? 0, 2) }}</h4>
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        @if(($order->shipping_cost ?? 0) > 0)
                                                        <tr class="table-order">
                                                            <td colspan="2">
                                                                <h5>Envío :</h5>
                                                            </td>
                                                            <td class="text-end" colspan="2">
                                                                <h4>${{ number_format($order->shipping_cost ?? 0, 2) }}</h4>
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        @if(($order->tax_amount ?? 0) > 0)
                                                        <tr class="table-order">
                                                            <td colspan="2">
                                                                <h5>Impuestos :</h5>
                                                            </td>
                                                            <td class="text-end" colspan="2">
                                                                <h4>${{ number_format($order->tax_amount ?? 0, 2) }}</h4>
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        <tr class="table-order">
                                                            <td colspan="2">
                                                                <h4 class="theme-color fw-bold">Total :</h4>
                                                            </td>
                                                            <td class="text-end" colspan="2">
                                                                <h4 class="theme-color fw-bold">${{ number_format($statistics['total'], 2) }}</h4>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-xl-4">
                                            <div class="order-success">
                                                <div class="row g-4">
                                                    <ul class="order-details">
                                                        <h4>Resumen</h4>
                                                        <li>Orden ID: <strong>#{{ $order->id }}</strong></li>
                                                        <li>Fecha de Orden: {{ $order->created_at->format('d/m/Y') }}</li>
                                                        <li>Estado: 
                                                            @php
                                                                $statusLabels = [
                                                                    'pending' => 'Pendiente',
                                                                    'paid' => 'Pagado',
                                                                    'shipped' => 'Enviado',
                                                                    'cancelled' => 'Cancelado'
                                                                ];
                                                                $statusColors = [
                                                                    'pending' => 'warning',
                                                                    'paid' => 'success',
                                                                    'shipped' => 'info',
                                                                    'cancelled' => 'danger'
                                                                ];
                                                            @endphp
                                                            <span class="mx-2 badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                                            </span>
                                                        </li>
                                                        <li>Productos: {{ $statistics['unique_products'] }} ({{ $statistics['total_products'] }} unidades)</li>
                                                        <li>Total: <strong>${{ number_format($statistics['total'], 2) }}</strong></li>
                                                    </ul>

                                                    <ul class="order-details">
                                                        <h4 class="mt-4">Cliente</h4>
                                                        <li><strong>{{ $order->user->name }}</strong></li>
                                                        <li>{{ $order->user->email }}</li>
                                                        @if($order->user->phone)
                                                            <li>Teléfono: {{ $order->user->phone }}</li>
                                                        @endif
                                                    </ul>

                                                    @if($order->userAddress)
                                                    <h4 class="mt-4">Dirección de Envío</h4>
                                                    <ul class="order-details">
                                                        <li>{{ $order->userAddress->address }}</li>
                                                        @if($order->userAddress->zone)
                                                            <li>{{ $order->userAddress->zone->name }}{{ $order->userAddress->zone->city ? ', ' . $order->userAddress->zone->city->name : '' }}</li>
                                                        @endif
                                                    </ul>
                                                    @endif

                                                    @if($order->payment)
                                                    <div class="payment-mode mt-4">
                                                        <h4>Información de Pago</h4>
                                                        <div class="p-3 bg-light rounded">
                                                            @if($order->payment->payment_method)
                                                                <p class="mb-1"><strong>Método:</strong> {{ ucfirst($order->payment->payment_method) }}</p>
                                                            @endif
                                                            @if($order->payment->transaction_id)
                                                                <p class="mb-1"><strong>ID Transacción:</strong> {{ $order->payment->transaction_id }}</p>
                                                            @endif
                                                            <p class="mb-0">
                                                                <strong>Estado:</strong>
                                                                <span class="badge bg-{{ $order->payment->status == 'completed' ? 'success' : ($order->payment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                                    {{ ucfirst($order->payment->status) }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <!-- Timeline -->
                                                    @if(count($timeline) > 0)
                                                    <div class="delivery-sec mt-4">
                                                        <h4>Historial</h4>
                                                        <div class="timeline mt-3">
                                                            @foreach($timeline as $event)
                                                            <div class="timeline-item mb-3">
                                                                <div class="d-flex">
                                                                    <div class="timeline-icon me-3">
                                                                        <i class="bi {{ $event['icon'] }}"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-1">{{ $event['event'] }}</h6>
                                                                        <p class="text-muted mb-1 small">{{ $event['description'] }}</p>
                                                                        <small class="text-muted">{{ $event['date']->format('d/m/Y H:i') }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <div class="mt-4 pt-3 border-top">
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary flex-fill">
                                                                <i class="ri-edit-line me-1"></i> Editar Orden
                                                            </a>
                                                            <a href="{{ route('orders.index') }}" class="btn btn-secondary flex-fill">
                                                                <i class="ri-arrow-left-line me-1"></i> Volver
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- section end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- tracking table end -->
        </div>
    </div>
</div>

<style>
    .table-image {
        flex-shrink: 0;
    }
    
    .table-image img {
        object-fit: cover;
    }
    
    .placeholder-img {
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 20px;
    }
    
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #dee2e6;
    }
    
    .timeline-icon {
        background-color: #f8f9fa;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .order-details {
        list-style: none;
        padding-left: 0;
        margin-bottom: 1.5rem;
    }
    
    .order-details li {
        margin-bottom: 0.5rem;
        color: #666;
        line-height: 1.5;
        
    }
    
    .order-details h4 {
        margin-bottom: 1rem;
        color: #333;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .payment-mode p {
        color: #666;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    
    .table-order td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    
    .table-order h5 {
        margin-bottom: 0;
        font-weight: 500;
    }
    
    .table-order p.text-muted {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    /* Para el thead completo */
thead {
    border-radius: 12px 12px 0 0; /* Solo redondea las esquinas superiores */
    overflow: hidden;
}

/* Para las celdas individuales */
thead tr.rouden th:first-child {
    border-top-left-radius: 12px;
}

thead tr.rouden th:last-child {
    border-top-right-radius: 12px;
}

/* Opcional: Para agregar un fondo y hacerlo más visible */
thead tr.rouden th {
    background-color: #f8f9fa; /* Color de fondo claro */
    padding: 12px 15px;
    border-bottom: 2px solid #dee2e6;
}
</style>

@endsection