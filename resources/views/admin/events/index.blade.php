@extends('admin.layouts.master')


@section('content')

@section('title', 'Eventos')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        {{-- card-tabla-Principal --}}
                        <div class="card card-table">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-2">
                                    <!-- Título -->
                                    <h3 class="fw-bold">Listado de eventos</h3>
                                    
                                    <!-- Botón crear producto -->
                                    <div class="right-options">
                                        <a class="btn btn-solid" href="{{ route('events.create') }}"> 
                                            + Evento
                                        </a>
                                    </div>

                                </div>

                            
                                 {{-- Tabla eventos, states, cities --}}
                                <div>
                                    <div class="table-responsive">
                                        <table class="table theme-table table-event" id="table_id">
                                            <thead>
                                                <tr>
                                                    <th>Banner</th>
                                                    <th>Nombre</th>
                                                    <th>Tipo</th>
                                                    <th>Fecha</th>
                                                    <th>Estado</th>
                                                    <th>Ciudad</th>
                                                    <th>Status</th>
                                                    <th>Opciones</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($events as $event)
                                                <tr>
                                                    {{-- imagen product --}}
                                                    <td>
                                                        <div class="table-image">
                                                            <img src="{{ asset('storage/' . $event->banner) }}" 
                                                            class="img-fluid rounded-circle shadow-lg" 
                                                            width="40" 
                                                            style="box-shadow: 0 2px 2px rgba(40, 40, 40, 0.1);">
                                                        </div>
                                                    </td>
                                                    <td class="text-nowrap">{{ $event->name }}</td>
                                                    <td class="text-nowrap">{{ $event->type ?? 'N/A' }}</td>
                                                    <td class="text-nowrap">
                                                        {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td class="text-nowrap">{{ $event->state->name}}</td>
                                                    <td class="text-nowrap">{{ $event->city->name }}</td>
                                                    <td>
                                                        @php
                                                            $badgeColors = [
                                                                'draft' => 'warning',
                                                                'published' => 'success',
                                                                'completed' => 'info',
                                                                'cancelled' => 'danger'
                                                            ];
                                                        @endphp
                                                        <span class="badge bg-{{ $badgeColors[$event->status] ?? 'secondary' }}">
                                                            {{ $event->status_label }}
                                                        </span>
                                                    </td>
                                                    
                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-between">
                                                            {{-- Preview --}}
                                                            <a href="javascript:void(0)" class="text-primary view-product" data-product-id="{{ $event->id }}">
                                                                <i class="ri-eye-line"></i>
                                                            </a>

                                                            {{-- Edit --}}
                                                            <a href="{{ route('events.edit', $event->id) }}" class="text-warning">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                                
                                                            {{-- Delete --}}
                                                            <a href="javascript:void(0)" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal{{ $event->id }}" 
                                                                class="text-danger">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Modal de Eliminación -->
                                                <div class="modal fade" id="deleteModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de eliminar el Evento "{{ $event->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <form action="{{ route('products.destroy', $event->id) }}" method="POST">
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
                                                                <h5 class="mt-3">No se encontraron eventos</h5>
                                                                <p>No hay resultados para "{{ request('search') }}"</p>
                                                                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                                                                    Ver todos los eventos
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="text-muted">
                                                                <i class="ri-inbox-line display-4"></i>
                                                                <h5 class="mt-3">No hay eventos registrados</h5>
                                                                <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">
                                                                    Crear primer evento
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>                                   
                                    
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Visualizar Evento -->
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

            <!-- Modal de Detalles del Evento -->
            <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="eventDetailModalLabel">
                                <i class="ri-information-line me-2"></i>Detalles del Evento
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="eventDetailContent">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2">Cargando información del evento...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Final Modal de Detalles del Evento -->
        </div>
    </div>
</div>

{{-- Notificación al crear un evento --}}
@if(session('create_success') && session('show_alert'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¡Evento Creado!',
                text: '{{ session('event_name') ?? 'Evento creado exitosamente' }}',
                icon: 'success',
                timer: 2500,
                timerProgressBar: false,
                showConfirmButton: false,
                position: 'center',
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                }
            });
        });
    </script>
@endif

{{-- Notificación al actualizar un evento --}}
@if(session('update_success') && session('show_alert'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¡Evento Actualizado!',
                text: '{{ session('event_name') ?? 'Evento actualizado exitosamente' }}',
                icon: 'success',
                timer: 2500,
                timerProgressBar: false,
                showConfirmButton: false,
                position: 'center',
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                }
            });
        });
    </script>
@endif

{{-- Notificación de error --}}
@if(session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¡Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'center',
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                }
            });
        });
    </script>
@endif

{{-- SweetAlert2 para errores --}}
@if(session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            backdrop: 'rgba(0,0,0,0.4)',
            toast: true,
            position: 'top-end',
            showClass: {
                popup: 'animate__animated animate__fadeInRight'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutRight'
            }
        });
    });
</script>

@endif


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

@push('scripts')

{{--  Modal detalles de evento selecionado ( Preview ) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Configurar el modal
        const modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
        
        // Escuchar clics en los botones de vista
        document.querySelectorAll('.view-product').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const eventId = this.getAttribute('data-product-id');
                console.log('ID del evento clickeado:', eventId);
                loadEventDetails(eventId);
            });
        });
        
        // Función para cargar los detalles del evento
        function loadEventDetails(eventId) {
            // Mostrar loading
            const content = document.getElementById('eventDetailContent');
            content.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando información del evento...</p>
                </div>
            `;
            
            // Abrir el modal
            modal.show();
            
            // Construir la URL correcta
            const url = `/admin/events/${eventId}`;
            console.log('URL de la petición:', url);
            
            // Petición AJAX
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Respuesta status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    renderEventDetails(data.data);
                } else {
                    content.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="ri-error-warning-line me-2"></i>
                            ${data.message || 'Error al cargar los datos del evento'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error detallado:', error);
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line me-2"></i>
                        Error al cargar los datos del evento. 
                        <br><small class="text-muted">${error.message}</small>
                    </div>
                `;
            });
        }
        
        // Función para renderizar los detalles del evento
        function renderEventDetails(event) {
            console.log('Renderizando evento:', event);
            const content = document.getElementById('eventDetailContent');
            
            // Badge de estado
            const statusBadges = {
                'draft': '<span class="badge bg-warning">Borrador</span>',
                'published': '<span class="badge bg-success">Publicado</span>',
                'completed': '<span class="badge bg-info">Completado</span>',
                'cancelled': '<span class="badge bg-danger">Cancelado</span>'
            };
            
            // Badge de tipo
            const typeBadges = {
                'mtb': '<span class="badge bg-primary">MTB</span>',
                'route': '<span class="badge bg-success">Route</span>',
                'downhill': '<span class="badge bg-danger">Downhill</span>',
                'enduro': '<span class="badge bg-warning">Enduro</span>',
                'sport': '<span class="badge bg-info">Sport</span>'
            };
            
            // Formatear fechas
            const eventDate = event.event_date ? new Date(event.event_date).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'N/A';
            
            const registrationDeadline = event.registration_deadline ? new Date(event.registration_deadline).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'N/A';
            
            const createdAt = event.created_at ? new Date(event.created_at).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'N/A';
            
            const updatedAt = event.updated_at ? new Date(event.updated_at).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'N/A';
            
            // Determinar la URL de la imagen
            const bannerUrl = event.banner ? `{{ asset('storage') }}/${event.banner}` : null;
            console.log('URL del banner:', bannerUrl);
            
            content.innerHTML = `
                <div class="row">
                    <!-- Banner -->
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <label class="fw-bold text-muted mb-2 d-block">Banner del Evento</label>
                            ${bannerUrl ? 
                                `<img src="${bannerUrl}" 
                                      class="img-fluid rounded shadow" 
                                      style="max-height: 200px; width: 100%; object-fit: cover;"
                                      alt="${event.name}"
                                      onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'bg-light rounded p-4\'><i class=\'ri-image-line\' style=\'font-size: 48px; color: #ccc;\'></i><p class=\'text-muted mt-2\'>Imagen no disponible</p></div>';">` :
                                `<div class="bg-light rounded p-4">
                                    <i class="ri-image-line" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Sin imagen</p>
                                </div>`
                            }
                        </div>
                    </div>
                    
                    <!-- Información principal -->
                    <div class="col-md-8">
                        <h4 class="mb-3">${event.name || 'Sin nombre'}</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-primary d-block">Tipo</small>
                                <span class="fw-bold">${typeBadges[event.type] || event.type || 'N/A'}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-primary d-block">Status</small>
                                <span class="fw-bold">${statusBadges[event.status] || event.status || 'N/A'}</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-primary d-block">Responsable</small>
                                <span class="fw-bold">${event.name_manager || 'N/A'}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-primary d-block">Celular</small>
                                <span class="fw-bold">${event.phone || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Detalles completos -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="ri-map-pin-line me-2"></i>Ubicación</h6>
                        <p class="mb-1"><strong>Estado:</strong> ${event.state ? event.state.name : 'N/A'}</p>
                        <p class="mb-1"><strong>Ciudad:</strong> ${event.city ? event.city.name : 'N/A'}</p>
                        <p class="mb-1"><strong>Dirección:</strong> ${event.address || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="ri-calendar-event-line me-2"></i>Fechas</h6>
                        <p class="mb-1"><strong>Fecha del Evento:</strong> ${eventDate}</p>
                        <p class="mb-1"><strong>Fecha Límite (Inscripciónes):</strong> ${registrationDeadline}</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary"><i class="ri-file-text-line me-2"></i>Descripción</h6>
                        <p class="p-2">${event.description || 'Sin descripción'}</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="ri-user-line me-2"></i>Usuario</h6>
                        <p class="mb-1"><strong>Nombre:</strong> ${event.user ? event.user.name : 'N/A'}</p>
                        <p class="mb-1"><strong>Email:</strong> ${event.user ? event.user.email : 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="ri-mail-line me-2"></i>Contacto</h6>
                        <p class="mb-1"><strong>Email:</strong> ${event.email_manager || 'N/A'}</p>
                    </div>
                </div>
                
                <hr>
                
                <!-- Fechas de creación/actualización -->
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Creado: ${createdAt}</small>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">Actualizado: ${updatedAt}</small>
                    </div>
                </div>
            `;
        }
        
    });
</script>
    
@endpush