@extends('admin.layouts.master')


@section('content')

@section('title', 'Productos')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">

            <div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            {{-- card-tabla-Principal --}}
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-flex align-items-center justify-content-between flex-wrap gap-0">
                        <!-- Título -->
                        <h5 class="mb-0">Listado de productos</h5>
                        
                        <!-- Barra de búsqueda -->
                        <div class="search-box flex-grow-1" style="max-width: 400px;">
                            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control form-control-sm" 
                                           placeholder="Buscar producto..." 
                                           value="{{ request('search') }}">
                                    @if(request('search'))
                                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm" type="button">
                                            <i class="ri-close-line"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Botón crear producto -->
                        <div class="right-options">
                            <a class="btn btn-solid" href="{{ route('products.create') }}"> 
                                + Producto
                            </a>
                        </div>
                    </div>

                    {{-- Mostrar término de búsqueda --}}
                    @if(request('search'))
                        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="ri-information-line me-2"></i>
                                Resultados para: <strong>"{{ request('search') }}"</strong>
                                <a href="{{ route('products.index') }}" class="btn-close ms-auto" aria-label="Close"></a>
                            </div>
                        </div>
                    @endif

                    <div>
                        <div class="table-responsive">
                            <table class="table theme-table table-product" id="table_id">
                                <thead>
                                    <tr>
                                        <th class="col-photo">Imagen</th>
                                        <th class="col-photo">CodeBar</th>
                                        <th class="col-name">Nombre</th>
                                        <th class="col-category">Categoria</th>
                                        <th class="col-category">Marca</th>
                                        <th class="col-price">Stock</th>
                                        <th class="col-price">Precio</th>
                                        <th class="col-price">Utilidad</th>
                                        <th class="col-price">Precio-2</th>
                                        <th class="col-price">Utilidad-2</th>
                                        <th class="col-actions">Opciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                    <tr>
                                        {{-- imagen product --}}
                                        <td>
                                            <div class="table-image">
                                                <img src="{{ $product->main_image_url }}" 
                                                     class="rounded-circle shadow" 
                                                     width="35"
                                                     alt="{{ $product->name }}">
                                            </div>
                                        </td>
                                        <td class="text-nowrap">{{ $product->codebar }}</td>
                                        <td class="text-nowrap">{{ $product->name }}</td>
                                        <td class="text-nowrap">{{ $product->category->name ?? 'N/A' }}</td>
                                        <td class="text-nowrap">{{ $product->brand->name ?? 'N/A' }}</td>
                                        <td class="text-nowrap">{{ $product->stock }}</td>
                                        <td class="td-price">${{ number_format($product->price, 2) }}</td>
                                        <td class="text-nowrap">{{ $product->utility_percentage }}</td>
                                        <td class="td-price">${{ number_format($product->price2, 2) }}</td>
                                        <td class="text-nowrap">{{ $product->utility_percentage }}</td>
                                        
                                        <td>
                                            <div class="d-flex gap-2 justify-content-between">
                                                <a href="javascript:void(0)" class="text-primary view-product" data-product-id="{{ $product->id }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('products.edit', $product->id) }}" class="text-warning">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <a href="javascript:void(0)" 
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#deleteModal{{ $product->id }}" 
                                                   class="text-danger">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal de Eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar Eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Estás seguro de eliminar el producto "{{ $product->name }}"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
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
                                                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                                                        Ver todos los productos
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-muted">
                                                    <i class="ri-inbox-line display-4"></i>
                                                    <h5 class="mt-3">No hay productos registrados</h5>
                                                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
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
                            @if($products->hasPages())
                            <div class="d-flex justify-content-center align-items-center mt-4">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Enlace Anterior --}}
                                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link border-0 bg-light text-dark" 
                                               href="{{ $products->appends(request()->query())->previousPageUrl() }}" 
                                               aria-label="Previous">
                                                <i class="ri-arrow-left-line"></i>
                                            </a>
                                        </li>
                                        
                                        {{-- Enlaces de páginas --}}
                                        @php
                                            $current = $products->currentPage();
                                            $last = $products->lastPage();
                                            $start = max(1, $current - 1);
                                            $end = min($last, $current + 1);
                                        @endphp
                                        
                                        {{-- Primera página --}}
                                        @if($start > 1)
                                            <li class="page-item">
                                                <a class="page-link border-0 bg-light text-dark" 
                                                   href="{{ $products->appends(request()->query())->url(1) }}">1</a>
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
                                                   href="{{ $products->appends(request()->query())->url($page) }}">
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
                                                   href="{{ $products->appends(request()->query())->url($last) }}">{{ $last }}</a>
                                            </li>
                                        @endif
                                        
                                        {{-- Enlace Siguiente --}}
                                        <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link border-0 bg-light text-dark" 
                                               href="{{ $products->appends(request()->query())->nextPageUrl() }}" 
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

<!-- Modal para Visualizar Producto -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productDetails">
                <!-- Los detalles se cargan via AJAX -->
            </div>
        </div>
    </div>
</div>
            <!-- Container-fluid Ends-->

            
        </div>
    </div>
</div>

<style>
    /* Estilos para compactar la tabla */
    .table-product {
        width: 100%;
        table-layout: auto;
        border-collapse: separate;
        border-spacing: 0 6px; /* Solo espaciado vertical entre filas */
    }

    .table-product thead th {
        white-space: nowrap;
        padding: 2px 2px; /* Reducir padding horizontal */
        font-size: 0.8rem;
        font-weight: 300;
    }


    .table-product tbody td {
        padding: 4px 2px; /* Reducir padding horizontal */
        vertical-align: middle;
        font-size: 0.79rem;
    }


    /* Columnas específicas con ajustes */
    .table-product td:first-child, 
    .table-product th:first-child {
        padding-left: 8px; /* Menor padding en primera columna */
    }

    .table-product td:last-child, 
    .table-product th:last-child {
        padding-right: 7px; /* Menor padding en última columna */
    }

    /* Ajustes para la columna de imagen */
    .table-image img {
        width: 30px; /* Tamaño fijo para imágenes */
        height: 30px;
        object-fit: cover;
        border-radius: 10px;
    }

    /* Ajustes para la columna de opciones */
    .table-product ul {
        display: flex;
        gap: 4px;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .table-product ul li a {
        display: inline-flex;
        padding: 3px;
        color: #6c757d;
        transition: color 0.2s;
    }

    .table-product ul li a:hover {
        color: #0d6efd;
    }

    /* Estado compacto */
    .status-danger span {
        padding: 4px 4px;
        font-size: 0.8rem;
        border-radius: 4px;
    }

    /* Precio con formato compacto */
    .td-price {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        white-space: nowrap;
    }
    /* Estilos para la paginación en negro */
    .page-link.text-black {
        color: #469e85 !important;
    }

    .page-link.text-black:hover {
        color: #469e85  !important;
    }

    .page-item.active .page-link.text-black {
        color: #fff !important; /* Texto blanco cuando está activo */
        background-color: #469e85  !important; /* Fondo negro cuando está activo */
        border-color: #469e85  !important;
    }

    .page-item.disabled .page-link.text-black {
        color: #6c757d !important; /* Color gris para enlaces deshabilitados */
    }
</style>

@endsection