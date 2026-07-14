@extends('admin.layouts.master')


@section('content')

@section('title', 'CxC')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">

            <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    {{-- card-Principal --}}
                    <div class="card card-table">
                        <div class="card-body">
                            
                            <div class="title-header option-title d-flex align-items-center justify-content-between flex-wrap gap-0">
                                <!-- Título -->
                                <h5 class="mb-0">Listado de Cuentas x Cobrar</h5>
                                
                               
                                <!-- Botón crear producto -->
                                <div class="right-options">
                                    <a class="btn btn-solid" href="{{ route('receivables.create') }}"> 
                                        + Cuenta
                                    </a>
                                </div>
                                
                            </div>

                            {{-- Mostrar término de búsqueda --}}
                            @if(request('search'))
                                <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-information-line me-2"></i>
                                        Resultados para: <strong>"{{ request('search') }}"</strong>
                                        <a href="{{ route('receivables.index') }}" class="btn-close ms-auto" aria-label="Close"></a>
                                    </div>
                                </div>
                            @endif

                            {{-- Tabla de listado de busqueda --}}
                            <div>
                                <div class="table-responsive">
                                    <table class="table theme-table table-receivable datatable-basic" id="receivablesTable">
                                        <thead>
                                            <tr>
                                                <th class="col-photo">Codigo</th>
                                                <th class="col-name">Cliente</th>
                                                <th class="col-category">Concepto</th>
                                                <th class="col-category">Total</th>
                                                <th class="col-price">Saldo</th>
                                                <th class="col-price">Estado</th>
                                                <th class="col-price">Vence</th>
                                                <th class="col-actions">Opciones</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($receivables as $receivable)
                                            <tr>
                                            
                                                <td class="text-nowrap">{{ $receivable->code }}</td>
                                                <td class="text-nowrap">{{ $receivable->user->name }}</td>
                                                <td class="text-nowrap">{{ Str::limit($receivable->concept, 30) }}</td>
                                                <td class="text-nowrap">{{ number_format($receivable->total_amount, 2) }}</td>
                                                <td class="td-price">${{ number_format($receivable->pending_balance, 2) }}</td>
                                               
                                                
                                                <td>
                                                    @php
                                                        $badgeClass = [
                                                            'pendiente' => 'warning',
                                                            'parcial' => 'info',
                                                            'pagado' => 'success',
                                                            'retrazo' => 'danger'
                                                        ][$receivable->status];
                                                    @endphp
                                                    <span class="badge bg-{{ $badgeClass }}">
                                                        {{ ucfirst($receivable->status) }}
                                                        @if($receivable->isOverdue())
                                                            ({{ $receivable->days_overdue }} días)
                                                        @endif
                                                    </span>
                                                </td>

                                                <td>
                                                    @if($receivable->due_date)
                                                        {{ $receivable->due_date->format('d/m/Y') }}
                                                        @if($receivable->due_date->isPast() && $receivable->pending_balance > 0)
                                                            <i class="bi bi-exclamation-triangle text-danger"></i>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>

                                                {{-- Botones de Opciones --}}
                                                    <td class="text-center">
                                                        <div class="action-buttons d-flex gap-1 justify-content-center">
                                                            {{-- Botón Ver --}}
                                                            <a href="{{ route('receivables.show', $receivable) }}" 
                                                            class="btn btn-sm btn-icon btn-outline-info" 
                                                            title="Ver detalles" 
                                                            data-bs-toggle="tooltip">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                            
                                                            {{-- Botón Registrar Pago (solo si hay saldo pendiente) --}}
                                                            @if($receivable->pending_balance > 0)
                                                            <a href="{{ route('receivables.payment.create', $receivable) }}" 
                                                            class="btn btn-sm btn-icon btn-outline-success" 
                                                            title="Registrar Pago" 
                                                            data-bs-toggle="tooltip">
                                                                <i class="ri-money-dollar-circle-line"></i>
                                                            </a>
                                                            @endif
                                                            
                                                            {{-- Botón Editar --}}
                                                            <a href="{{ route('receivables.edit', $receivable) }}" 
                                                            class="btn btn-sm btn-icon btn-outline-primary" 
                                                            title="Editar" 
                                                            data-bs-toggle="tooltip">
                                                                <i class="ri-edit-2-line"></i>
                                                            </a>
                                                        </div>
                                                    </td>


                                            </tr>

                                            <!-- Modal de Eliminación -->
                                            <div class="modal fade" id="deleteModal{{ $receivable->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmar Eliminación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Estás seguro de eliminar el producto "{{ $receivable->name }}"?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('receivables.destroy', $receivable->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    @if(request('search'))
                                                        <div class="text-muted">
                                                            <i class="ri-search-line display-4"></i>
                                                            <h5 class="mt-3">No se encontraron productos</h5>
                                                            <p>No hay resultados para "{{ request('search') }}"</p>
                                                            <a href="{{ route('receivables.index') }}" class="btn btn-primary btn-sm">
                                                                Ver todos los productos
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line display-4"></i>
                                                            <h5 class="mt-3">No hay productos registrados</h5>
                                                            <a href="{{ route('receivables.create') }}" class="btn btn-primary btn-sm">
                                                                Crear primer producto
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    {{-- Paginación --}}
                                    @if($receivables->hasPages())
                                    <div class="d-flex justify-content-center align-items-center mt-4">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-sm mb-0">
                                                {{-- Enlace Anterior --}}
                                                <li class="page-item {{ $receivables->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link border-0 bg-light text-dark" 
                                                    href="{{ $receivables->appends(request()->query())->previousPageUrl() }}" 
                                                    aria-label="Previous">
                                                        <i class="ri-arrow-left-line"></i>
                                                    </a>
                                                </li>
                                                
                                                {{-- Enlaces de páginas --}}
                                                @php
                                                    $current = $receivables->currentPage();
                                                    $last = $receivables->lastPage();
                                                    $start = max(1, $current - 1);
                                                    $end = min($last, $current + 1);
                                                @endphp
                                                
                                                {{-- Primera página --}}
                                                @if($start > 1)
                                                    <li class="page-item">
                                                        <a class="page-link border-0 bg-light text-dark" 
                                                        href="{{ $receivables->appends(request()->query())->url(1) }}">1</a>
                                                    </li>
                                                    @if($start > 2)
                                                        <li class="page-item disabled">
                                                            <span class="page-link border-0 bg-light">...</span>
                                                        </li>
                                                    @endif
                                                @endif
                                                
                                                {{-- Páginas alrededor de la actual --}}
                                                @for ($page = $start; $page <= $end; $page++)
                                                    <li class="page-item {{ $current == $page ? 'active' : '' }}">
                                                        <a class="page-link border-0 {{ $current == $page ? 'bg-primary text-white' : 'bg-light text-dark' }}" 
                                                        href="{{ $receivables->appends(request()->query())->url($page) }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endfor
                                                
                                                {{-- Última página --}}
                                                @if($end < $last)
                                                    @if($end < $last - 1)
                                                        <li class="page-item disabled">
                                                            <span class="page-link border-0 bg-light">...</span>
                                                        </li>
                                                    @endif
                                                    <li class="page-item">
                                                        <a class="page-link border-0 bg-light text-dark" 
                                                        href="{{ $receivables->appends(request()->query())->url($last) }}">{{ $last }}</a>
                                                    </li>
                                                @endif
                                                
                                                {{-- Enlace Siguiente --}}
                                                <li class="page-item {{ !$receivables->hasMorePages() ? 'disabled' : '' }}">
                                                    <a class="page-link border-0 bg-light text-dark" 
                                                    href="{{ $receivables->appends(request()->query())->nextPageUrl() }}" 
                                                    aria-label="Next">
                                                        <i class="ri-arrow-right-line"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                    @endif
                                </div>
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
    /* ESTILOS CORREGIDOS PARA LA TABLA Y BOTONES */
    
    /* Asegurar que los botones sean visibles */
    .action-buttons {
        min-width: 120px;
        visibility: visible !important;
        opacity: 1 !important;
        display: flex !important;
    }
    
    /* Estilos para botones pequeños con iconos */
    .btn-icon {
        width: 30px;
        height: 30px;
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
    
    .btn-icon.btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }
    
    .btn-icon.btn-outline-success {
        color: #198754;
        border-color: #198754;
    }
    
    .btn-icon.btn-outline-success:hover {
        background-color: #198754;
        color: white;
    }
    
    .btn-icon.btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-icon.btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    /* Asegurar que la columna de opciones tenga suficiente ancho */
    .col-actions {
        min-width: 140px;
        width: 140px;
    }
    
    /* Corregir el espaciado de la tabla */
    .table-product tbody td {
        padding: 8px 4px !important;
        vertical-align: middle;
        font-size: 0.79rem;
    }
    
    /* Asegurar que las celdas no se superpongan */
    .table-product td {
        white-space: nowrap;
        overflow: visible;
    }
    
    /* Corregir el contenedor de la tabla */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Asegurar que los botones no se corten */
    .table-product tbody tr {
        position: relative;
        z-index: 1;
    }
    
    .table-product tbody tr:hover {
        z-index: 2;
    }
    
    /* Tooltips */
    [data-bs-toggle="tooltip"] {
        position: relative;
    }
</style>

@endsection

@push('scripts')
    <script>
       
    
    </script>
@endpush