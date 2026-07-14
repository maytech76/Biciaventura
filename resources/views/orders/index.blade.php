@extends('admin.layouts.master')


@section('content')

@section('title', 'Ordenes')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- card-Principal -->
                        <div class="card card-table">
                            <div class="card-body">
                                <div class="title-header option-title d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">Listado de Ordenes</h5>
                                    <a href="{{-- {{route('orders.create')}} --}}" class="btn btn-theme d-flex align-items-center">
                                        <i data-feather="plus-square" class="me-1"></i>
                                        <span>+ Nueva Orden</span>
                                    </a>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                        <i class="ri-check-line me-2"></i>
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="table-responsive mt-3">
                                    <!-- REMOVER el div extra que envuelve la tabla -->
                                    <table class="table theme-table table-orders datatable-basic" id="ordersTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Usuario</th>
                                                <th>Total</th>
                                                <th>Estado</th>
                                                <th>Fecha</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td>${{ number_format($order->total_price, 2) }}</td>
                                                <td>
                                                    @if($order->status == 'pending')
                                                        <span class="badge bg-warning">Pendiente</span>
                                                    @elseif($order->status == 'paid')
                                                        <span class="badge bg-success">Pagado</span>
                                                    @elseif($order->status == 'shipped')
                                                        <span class="badge bg-primary">Enviado</span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelado</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <!-- Cambiar a una estructura más simple -->
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{route('orders.orderdetails', $order->id)}} " 
                                                           class="btn btn-sm" 
                                                           data-order-id="{{ $order->id }}"
                                                           title="Ver detalles"
                                                           data-bs-toggle="tooltip"
                                                            target="_blank">
                                                            <i class="ri-eye-line"></i>
                                                        </a>

                                                        <a href="{{route('dispatched.ordedispatched', $order->id)}}" class="btn btn-sm" 
                                                        data-order-id="{{ $order->id }}"
                                                        title="Despacho">                                                                                           
                                                            <i class="ri-file-fill text-warning"></i>
                                                        </a>

                                                        <a href="{{ route('orders.edit', $order->id) }}" 
                                                           class="btn btn-sm"
                                                           title="Editar">
                                                            <i class="ri-edit-2-line"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" 
                                                           class="btn btn-sm"
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#deleteModal{{ $order->id }}"
                                                           title="Eliminar">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <!-- Modal de Eliminación -->
                                            <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmar Eliminación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Estás seguro de eliminar la orden #{{ $order->id }}?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                    <!-- ELIMINAR la paginación de Laravel cuando usas DataTables -->
                                    <!-- DataTables manejará su propia paginación -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para los botones de acción */
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .btn-icon.btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
    }
    
    
    
    .btn-icon.btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
    }
    
    
    
    .btn-icon.btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }
    
    
    
    /* Asegurar que DataTables se vea bien */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        padding: 1rem 0;
    }
</style>

@endsection

{{-- ELIMINA el @push('scripts') completo --}}
{{-- El layout ya inicializa DataTables automáticamente --}}