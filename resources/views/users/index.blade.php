@extends('admin.layouts.master')

@section('title', 'Usuarios')

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
                                <div class="title-header option-title">
                                    <h5>Listado de Usuarios</h5>
                                    <form class="d-inline-flex">
                                        <a href="{{-- {{route('orders.create')}} --}}" class="align-items-center btn btn-theme d-flex">
                                            <i data-feather="plus-square"></i>+ Nuevo
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
                                                    <th>#</th>
                                                    <th style="text-align: left">Nombre</th>
                                                    <th style="text-align: left">Email</th>
                                                    {{-- <th style="text-align: left">Telefono</th> --}}
                                                    <th>Perfil</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($users as $user )
                                                <tr>

                                                    <td>{{ $user->id }}</td>

                                                    <td style="text-align: left">{{ $user->name }}</td>

                                                    <td style="text-align: left">{{ $user->email }}</td>

                                                    {{-- <td style="text-align: left">{{ $user->phone }}</td> --}}

                                                    <td>{{ $user->role }}</td>


                                                   
                                                    
                                                    
                                                
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:void(0)" class="view-category" data-category-id="{{ $user->id }}">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="{{ route('orders.edit', $user->id) }}">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   

                                                <!-- Modal de Eliminación -->
                                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que quieres eliminar la categoría "{{ $user->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <form action="{{ route('orders.destroy', $user->id) }}" method="POST">
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
                                                    <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                                        <a class="page-link text-black" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                                            <span class="text-black" aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                    
                                                    {{-- Enlaces de páginas --}}
                                                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                                        <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                                                            <a class="page-link text-black" href="{{ $url }}">{{ $page }}</a>
                                                        </li>
                                                    @endforeach
                                                    
                                                    {{-- Enlace Siguiente --}}
                                                    <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                                                        <a class="page-link text-black" href="{{ $users->nextPageUrl() }}" aria-label="Next">
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

@endsection