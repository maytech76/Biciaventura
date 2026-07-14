@extends('admin.layouts.master')

@section('title', 'Categorias')

@section('content')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- card-Principal -->
                        <div class="card card-table">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-2">
                                    <h3 class="fw-bold">Listado de categorias</h3>
                                    <form class="d-inline-flex">
                                        <a href="{{route('categories.create')}}" class="align-items-center btn btn-theme d-flex">
                                            <i data-feather="plus-square"></i>+ Nueva
                                        </a>
                                    </form>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="table-responsive category-table">
                                    <div>
                                        <table class="table all-package theme-table" id="table_id">
                                            <thead>
                                                <tr>
                                                    <th>Imagen</th>
                                                    <th>Nombre</th>
                                                    <th>Creado</th>
                                                    <th>Slug</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($categories as $category )
                                                <tr>
                                                    <td>
                                                        <div class="table-image">
                                                            @if($category->photo)
                                                                <img src="{{ asset('storage/' . $category->photo) }}" 
                                                                     class="img-fluid rounded-circle shadow-lg" 
                                                                     width="40" 
                                                                     style="box-shadow: 0 2px 2px rgba(40, 40, 40, 0.1);">
                                                            @else
                                                                <img src="{{ asset('assets/images/default-category.png') }}" 
                                                                     class="img-fluid rounded-circle shadow-lg" 
                                                                     width="40" 
                                                                     style="box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <td>{{ $category->name }}</td>

                                                    <td>{{ $category->created_at->format('d/m/Y') }}</td>
                                                    
                                                    <td>{{ $category->slug }}</td>
                                                
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:void(0)" class="view-category" data-category-id="{{ $category->id }}">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="{{ route('categories.edit', $category->id) }}">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   

                                                <!-- Modal de Eliminación -->
                                                <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que quieres eliminar la categoría "{{ $category->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
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

                                        {{-- Paginacion --}}
                                        <div class="d-flex justify-content-center align-items-center mt-3">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination justify-content-end mb-0">
                                                    {{-- Enlace Anterior --}}
                                                    <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                                                        <a class="page-link text-black" href="{{ $categories->previousPageUrl() }}" aria-label="Previous">
                                                            <span class="text-black" aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                    
                                                    {{-- Enlaces de páginas --}}
                                                    @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                                        <li class="page-item {{ $categories->currentPage() == $page ? 'active' : '' }}">
                                                            <a class="page-link text-black" href="{{ $url }}">{{ $page }}</a>
                                                        </li>
                                                    @endforeach
                                                    
                                                    {{-- Enlace Siguiente --}}
                                                    <li class="page-item {{ !$categories->hasMorePages() ? 'disabled' : '' }}">
                                                        <a class="page-link text-black" href="{{ $categories->nextPageUrl() }}" aria-label="Next">
                                                            <span class="text-black" aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
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
</div>

<!-- Modal para Visualizar Categoría -->
<div class="modal fade" id="viewCategoryModal" tabindex="-1" aria-labelledby="viewCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCategoryModalLabel">Detalles de la Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($category->photo ?? false)
                            <img id="categoryPhoto" src="" class="img-fluid rounded-circle shadow-lg" alt="Category Photo" style="max-height: 200px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                        @else
                            <img id="categoryPhoto" src="{{ asset('assets/images/default-category.png') }}" class="img-fluid rounded-circle shadow-lg" style="max-height: 200px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nombre:</th>
                                <td id="categoryName"></td>
                            </tr>
                            <tr>
                                <th>Slug:</th>
                                <td id="categorySlug"></td>
                            </tr>
                            <tr>
                                <th>Descripción:</th>
                                <td id="categoryDescription"></td>
                            </tr>
                            <tr>
                                <th>Creado:</th>
                                <td id="categoryCreated"></td>
                            </tr>
                            <tr>
                                <th>Actualizado:</th>
                                <td id="categoryUpdated"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.view-category').on('click', function() {
            const categoryId = $(this).data('category-id');
            
            $.ajax({
                url: '/categories/' + categoryId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const category = response.category;
                        
                        // Actualizar la imagen en el modal
                        if (category.photo_url) {
                            $('#categoryPhoto').attr('src', category.photo_url);
                        } else {
                            $('#categoryPhoto').attr('src', '{{ asset('assets/images/default-category.png') }}');
                        }
                        
                        $('#categoryName').text(category.name);
                        $('#categorySlug').text(category.slug);
                        $('#categoryDescription').text(category.description || 'Sin descripción');
                        $('#categoryCreated').text(category.created_at);
                        $('#categoryUpdated').text(category.updated_at);
                        
                        $('#viewCategoryModal').modal('show');
                    }
                },
                error: function(xhr) {
                    alert('Error al cargar los datos de la categoría');
                }
            });
        });
    });
</script>
@endpush