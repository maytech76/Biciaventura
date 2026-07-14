@extends('admin.layouts.master')

@section('title', 'Ciudades')

@section('content')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table">
                            <div class="card-body">
                                <div class="title-header option-title">
                                    <h5>Listado de Ciudades</h5>
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

                                {{-- Tabla Listado de Ciudades y Zonas --}}
                                <div class="table-responsive category-table">
                                    <div>
                                        <table class="table all-package theme-table" id="table_id">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Zonas</th>
                                                    <th>Creado</th>
                                                    <th>Status</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($cities as $city )
                                                <tr>
                                                    <td>{{ $city->name }}</td>
                                                    <td>
                                                        <span class="badge {{ $city->zones_count > 0 ? 'bg-warning' : 'bg-danger' }}">
                                                            {{ $city->zones_count }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $city->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($city->status == 1)
                                                            <span class="badge bg-success">Activa</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactiva</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:void(0)" class="view-city" 
                                                                   data-bs-toggle="modal" 
                                                                   data-bs-target="#viewCityModal"
                                                                   data-city-id="{{ $city->id }}">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('categories.edit', $city->id) }}">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $city->id }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   
                                                <!-- Modal de Eliminación -->
                                                <div class="modal fade" id="deleteModal{{ $city->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que quieres eliminar la categoría "{{ $city->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <form action="{{ route('categories.destroy', $city->id) }}" method="POST">
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
                                                    <li class="page-item {{ $cities->onFirstPage() ? 'disabled' : '' }}">
                                                        <a class="page-link text-black" href="{{ $cities->previousPageUrl() }}" aria-label="Previous">
                                                            <span class="text-black" aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                    {{-- Enlaces de páginas --}}
                                                    @foreach ($cities->getUrlRange(1, $cities->lastPage()) as $page => $url)
                                                        <li class="page-item {{ $cities->currentPage() == $page ? 'active' : '' }}">
                                                            <a class="page-link text-black" href="{{ $url }}">{{ $page }}</a>
                                                        </li>
                                                    @endforeach
                                                    {{-- Enlace Siguiente --}}
                                                    <li class="page-item {{ !$cities->hasMorePages() ? 'disabled' : '' }}">
                                                        <a class="page-link text-black" href="{{ $cities->nextPageUrl() }}" aria-label="Next">
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

<!-- Modal para ver detalles de la ciudad -->
<div class="modal fade" id="viewCityModal" tabindex="-1" aria-labelledby="viewCityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewCityModalLabel">
                    <i class="ri-information-line me-2"></i>Detalles de la Ciudad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Nombre:</strong>
                            <div id="modalCityName" class="text-muted">Cargando...</div>
                        </div>
                        <div class="mb-3">
                            <strong>Estado:</strong>
                            <div id="modalCityStatus">Cargando...</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Número de Zonas:</strong>
                            <div id="modalCityZones" class="text-muted">Cargando...</div>
                        </div>
                        <div class="mb-3">
                            <strong>Fecha de Creación:</strong>
                            <div id="modalCityCreated" class="text-muted">Cargando...</div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Zonas -->
                <div class="mt-4">
                    <h6 class="border-bottom pb-2">
                        <i class="ri-map-pin-line me-2"></i>Zonas de la Ciudad
                    </h6>
                    <div id="zonesList">
                        <div class="text-center text-muted py-3">
                            <i class="ri-loader-4-line ri-spin fs-4"></i>
                            <p class="mt-2">Cargando zonas...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Usar delegación de eventos para manejar los clicks
        document.addEventListener('click', function(e) {
            if (e.target.closest('.view-city')) {
                const button = e.target.closest('.view-city');
                const cityId = button.getAttribute('data-city-id');
                
                console.log('City ID clicked:', cityId);
                
                if (cityId) {
                    loadCityDetails(cityId);
                }
            }
        });
        
        function loadCityDetails(cityId) {
            // Mostrar loading
            showLoadingState();
            
            // URL CORREGIDA con el prefijo admin
            const url = `/admin/cities/${cityId}/details`;
            console.log('Fetching URL:', url);
            
            // Hacer petición AJAX
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    if (data.success) {
                        updateModalWithData(data.data);
                    } else {
                        showError(data.message || 'Error al cargar los datos');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    showError('Error al conectar con el servidor: ' + error.message);
                });
        }
        
        function showLoadingState() {
            document.getElementById('modalCityName').textContent = 'Cargando...';
            document.getElementById('modalCityStatus').innerHTML = '<span class="badge bg-secondary">Cargando...</span>';
            document.getElementById('modalCityZones').textContent = 'Cargando...';
            document.getElementById('modalCityCreated').textContent = 'Cargando...';
            document.getElementById('zonesList').innerHTML = `
                <div class="text-center text-muted py-3">
                    <i class="ri-loader-4-line ri-spin fs-4"></i>
                    <p class="mt-2">Cargando zonas...</p>
                </div>
            `;
        }
        
        function updateModalWithData(city) {
            console.log('Updating modal with:', city);
            
            // Actualizar información básica
            document.getElementById('modalCityName').textContent = city.name || 'No disponible';
            document.getElementById('modalCityZones').textContent = city.zones_count || '0';
            document.getElementById('modalCityCreated').textContent = city.created_at || 'No disponible';
            
            // Actualizar estado
            const statusBadge = city.status == 1 
                ? '<span class="badge bg-success">Activa</span>' 
                : '<span class="badge bg-danger">Inactiva</span>';
            document.getElementById('modalCityStatus').innerHTML = statusBadge;
            
            // Actualizar lista de zonas
            updateZonesList(city.zones || []);
        }
        
        function updateZonesList(zones) {
            const zonesList = document.getElementById('zonesList');
            
            if (!zones || zones.length === 0) {
                zonesList.innerHTML = `
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>No hay zonas registradas para esta ciudad
                    </div>
                `;
                return;
            }
            
            let zonesHtml = '<div class="table-responsive"><table class="table table-sm table-striped">';
            zonesHtml += `
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
            `;
            
            zones.forEach(zone => {
                zonesHtml += `
                    <tr>
                        <td>${zone.name || 'N/A'}</td>
                        <td>$${zone.price || '0.00'}</td>
                        <td>${zone.status_badge || '<span class="badge bg-secondary">N/A</span>'}</td>
                    </tr>
                `;
            });
            
            zonesHtml += '</tbody></table></div>';
            zonesList.innerHTML = zonesHtml;
        }
        
        function showError(message) {
            document.getElementById('modalCityName').textContent = 'Error';
            document.getElementById('modalCityStatus').innerHTML = '<span class="badge bg-danger">Error</span>';
            document.getElementById('modalCityZones').textContent = 'N/A';
            document.getElementById('modalCityCreated').textContent = 'N/A';
            document.getElementById('zonesList').innerHTML = `
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>${message}
                </div>
            `;
        }
    });
</script>
@endpush