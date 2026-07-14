@extends('admin.layouts.master')

@section('title', 'Bicicletas')

@section('styles')
    <!-- DataTable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="compact-wrapper">
        <div class="page-body-wrapper">
            <div class="page-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table">
                                <div class="card-body">
                                    
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-3">
                                        <h3 class="fw-bold">Listado de Bicicletas</h3>
                                        <div class="d-flex gap-2">
                                            <!-- Filtro de estado -->
                                            <select class="form-select" id="filterStatus" style="width: auto;">
                                                <option value="all">Todos los estados</option>
                                                <option value="1">Activos</option>
                                                <option value="0">Inactivos</option>
                                            </select>
                                            
                                            <a href="javascript:void(0)" 
                                                class="align-items-center btn btn-theme d-flex" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#createVehicleModal">
                                                <i data-feather="plus-square"></i>+ Nueva
                                            </a>
                                        </div>
                                    </div>

                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    {{-- Tabla de Bicicletas--}}
                                    <div class="table-responsive" style="overflow-x: visible !important;">
                                        <table class="table table-striped table-hover" id="vehiclesTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="font-size: 14px;">Tipo</th>
                                                    <th style="font-size: 14px;">Marca</th>
                                                    <th style="font-size: 14px;">Modelo</th>
                                                    <th style="font-size: 14px;">Propietario</th>
                                                    <th style="font-size: 14px;">Status</th>
                                                    <th style="font-size: 14px;">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($vehicles as $vehicle)
                                                <tr>
                                                    <td class="text-start">
                                                        <span class="text-info">{{ $vehicle->type }}</span>
                                                    </td>
                                                    <td class="text-start">{{ $vehicle->brand->name }}</td>
                                                    <td class="text-start">{{ $vehicle->model }}</td>
                                                    <td class="text-start">{{ $vehicle->user->name }}</td>
                                                    <td class="text-start">
                                                        <span class="{{ $vehicle->status == 1 ? 'text-success' : 'text-danger' }}">
                                                            {{ $vehicle->status == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <ul class="list-unstyled d-flex gap-2 mb-0">
                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                    class="text-warning edit-vehicle" 
                                                                    data-id="{{ $vehicle->id }}"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editVehicleModal">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                   class="delete-vehicle" 
                                                                   data-id="{{ $vehicle->id }}"
                                                                   data-name="{{ $vehicle->brand->name }} {{ $vehicle->model }}">
                                                                   <i class="ri-delete-bin-line" style="color: #a72b38"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                                                            <p class="mt-2">No hay Bicicletas registradas</p>
                                                        </div>
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
        </div>
    </div>

    <!-- Modal de Creación -->
    <div class="modal fade" id="createVehicleModal" tabindex="-1" aria-labelledby="createVehicleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #3c8749 0%, #23892a 100%); color: rgb(18, 17, 17);">
                    <h5 class="modal-title" style="color: rgb(186, 227, 174);" id="createVehicleModalLabel">
                        Nueva Bicicleta
                    </h5>
                  
                </div>
                <form action="{{ route('vehicles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo de Bicicleta</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="Mtb">Mtb</option>
                                <option value="Ruta">Ruta</option>
                                <option value="Enduro">Enduro</option>
                                <option value="Bmx">Bmx</option>
                                <option value="Niños">Niños</option>
                                <option value="E-Bike">E-Bike</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Marca</label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                                <option value="">Seleccionar marca</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="model" class="form-label">Modelo</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" placeholder="Ej: XT-700" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Propietario</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Seleccionar propietario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="fw-semibold">Status</span>
                            <label class="switch sm-switch mb-0">
                                <input type="hidden" name="status" value="0"> <!-- Campo hidden -->
                                <input type="checkbox" name="status" id="status" value="1" 
                                    {{ old('status') ? 'checked' : '' }}>
                                <span class="switch-state"></span>
                            </label>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #f4fb93 0%, #dadf41 100%); color: rgb(25, 24, 24);">
                    <h5 class="modal-title" style="color: rgb(31, 30, 30);" id="editVehicleModalLabel">
                        <i class="ri-edit-line me-2"></i>Editar Bicicleta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editVehicleForm" action="{{ route('vehicles.update', ['vehicle' => $vehicle->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        
                        <div class="mb-3">
                            <label for="edit_type" class="form-label">Tipo de Bicicleta</label>
                            <select class="form-select" id="edit_type" name="type" required>
                                <option value="Mtb">Mtb</option>
                                <option value="Ruta">Ruta</option>
                                <option value="Enduro">Enduro</option>
                                <option value="Bmx">Bmx</option>
                                <option value="Niños">Niños</option>
                                <option value="E-Bike">E-Bike</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_brand_id" class="form-label">Marca</label>
                            <select class="form-select" id="edit_brand_id" name="brand_id" required>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_model" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="edit_model" name="model" placeholder="Ej: XT-700" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_user_id" class="form-label">Propietario</label>
                            <select class="form-select" id="edit_user_id" name="user_id" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Switch con el mismo estilo que el modal de creación -->
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="fw-semibold">Status</span>
                            <label class="switch sm-switch mb-0">
                                <input type="hidden" name="status" value="0"> <!-- Campo hidden -->
                                <input type="checkbox" name="status" id="edit_status" value="1">
                                <span class="switch-state"></span>
                            </label>
                            <span id="editStatusText" class="fw-bold" style="font-size: 14px;">INACTIVO</span>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Switch pequeño (versión SM) */
        .switch.sm-switch {
            position: relative;
            display: inline-block;
            width: 42px;  /* Ancho reducido */
            height: 22px; /* Altura reducida */
        }

        .switch.sm-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch.sm-switch .switch-state {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 22px; /* Ajustado a la nueva altura */
        }

        .switch.sm-switch .switch-state:before {
            position: absolute;
            content: "";
            height: 18px;  /* Tamaño reducido */
            width: 18px;   /* Tamaño reducido */
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        .switch.sm-switch input:checked + .switch-state {
            background-color: #2ebe93; /* Color de Bootstrap para elementos activos */
        }
        
        .switch.sm-switch input:focus + .switch-state {
            box-shadow: 0 0 1px #2ebe93;
        }
        
        .switch.sm-switch input:checked + .switch-state:before {
            transform: translateX(20px); /* Ajustado al nuevo ancho */
        }
    </style>
@endpush

@push('scripts')
    <!-- DataTable JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SCRIPT 1: DataTable y funcionalidades generales -->
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            var table = $('#vehiclesTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                columnDefs: [
                    {
                        targets: 5,
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[1, 'asc']],
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Todos"]
                ],
            });

            // Filtro de estado
            $('#filterStatus').on('change', function() {
                var status = $(this).val();
                if (status === 'all') {
                    table.column(4).search('').draw();
                } else {
                    table.column(4).search(status === '1' ? 'ACTIVO' : 'INACTIVO').draw();
                }
            });

            // Eliminar/Desactivar con SweetAlert2
            $('.delete-vehicle').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');
                
                Swal.fire({
                    title: '¿Desactivar bicicleta?',
                    text: 'La bicicleta "' + name + '" será desactivada. ¿Estás seguro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, desactivar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/vehicles/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Desactivado!',
                                    text: 'La bicicleta ha sido desactivada exitosamente.',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudo desactivar la bicicleta'
                                });
                            }
                        });
                    }
                });
            });

            // Mostrar mensajes de éxito/error con SweetAlert
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            @endif
        });
    </script>

    <!-- SCRIPT 2: Funcionalidades del modal de edición -->
    <script>
        $(document).ready(function() {
            // Función para actualizar el texto del status en el modal de edición
            function updateEditStatusText(checked) {
                const statusText = $('#editStatusText');
                if (checked) {
                    statusText.text('ACTIVO').css('color', '#28a745');
                } else {
                    statusText.text('INACTIVO').css('color', '#dc3545');
                }
            }

            // Cargar datos para editar
            $('.edit-vehicle').on('click', function() {
                var id = $(this).data('id');
                
                // Mostrar loading
                Swal.fire({
                    title: 'Cargando...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: '/vehicles/' + id + '/edit',
                    type: 'GET',
                    success: function(data) {
                        Swal.close();
                        
                        // Cargar datos en el formulario
                        $('#edit_id').val(data.vehicle.id);
                        $('#edit_type').val(data.vehicle.type);
                        $('#edit_brand_id').val(data.vehicle.brand_id);
                        $('#edit_model').val(data.vehicle.model);
                        $('#edit_user_id').val(data.vehicle.user_id);
                        
                        // Manejar el checkbox y el texto
                        var isChecked = data.vehicle.status == 1;
                        $('#edit_status').prop('checked', isChecked);
                        updateEditStatusText(isChecked);
                        
                        // Actualizar la acción del formulario
                        $('#editVehicleForm').attr('action', '/vehicles/' + id);
                        
                        // Mostrar el modal
                        $('#editVehicleModal').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo cargar la información de la bicicleta',
                            footer: xhr.responseJSON?.message || 'Error desconocido'
                        });
                    }
                });
            });

            // Evento para actualizar el texto cuando cambia el checkbox en edición
            $('#edit_status').on('change', function() {
                updateEditStatusText($(this).is(':checked'));
            });

            // Opcional: Resetear el formulario al cerrar el modal
            $('#editVehicleModal').on('hidden.bs.modal', function() {
                // Limpiar mensajes de error si los hay
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').remove();
            });

            // Opcional: Prevenir el envío del formulario si hay campos vacíos
            $('#editVehicleForm').on('submit', function(e) {
                var isValid = true;
                $(this).find('input[required], select[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor completa todos los campos requeridos'
                    });
                }
            });
        });
    </script>
@endpush