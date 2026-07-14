@extends('admin.layouts.master')

@section('title', 'Mecanicos')

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
                                        <h3 class="fw-bold">Listado de Mecanicos</h3>
                                        <div class="d-flex gap-2">

                                            <!-- Filtro de estado -->
                                            <select class="form-select" id="filterStatus" style="width: auto;">
                                                <option value="all">Todos los estados</option>
                                                <option value="1">Activos</option>
                                                <option value="0">Inactivos</option>
                                            </select>

                                            <a href="javascript:void(0)" class="align-items-center btn btn-theme d-flex"
                                                data-bs-toggle="modal" data-bs-target="#createMechancModal">
                                                <i data-feather="plus-square"></i>+ Nuevo
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Inicio de la tabla de mecanicos --}}
                                    <div class="table-responsive" style="overflow-x: visible !important;">
                                        <table class="table table-striped table-hover" id="mechanicsTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="font-size: 14px;">Doc</th>
                                                    <th style="font-size: 14px;">Nombre</th>
                                                    <th style="font-size: 14px;">Teléfono</th>
                                                    <th style="font-size: 14px;">Email</th>
                                                    <th style="font-size: 14px;">Status</th>
                                                    <th style="font-size: 14px;">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($mechanics as $mechanic)
                                                <tr>
                                                    <td class="text-start">
                                                        <span class="text-info">{{ $mechanic->document }}</span>
                                                    </td>
                                                    <td class="text-start">{{ $mechanic->full_name }}</td>
                                                    <td class="text-start">{{ $mechanic->phone ?? 'N/A' }}</td>
                                                    <td class="text-start">{{ $mechanic->email }}</td>
                                                    <td class="text-start">
                                                        <span class="{{ $mechanic->status == 1 ? 'text-success' : 'text-danger' }}">
                                                            {{ $mechanic->status == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <ul class="list-unstyled d-flex gap-2 mb-0">
                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                    class="text-warning edit-mechanic" 
                                                                    data-id="{{ $mechanic->id }}"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editMechancModal">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                   class="delete-mechanic" 
                                                                   data-id="{{ $mechanic->id }}"
                                                                   data-name="{{ $mechanic->full_name }}">
                                                                   <i class="ri-delete-bin-line" style="color: #a72b38"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <!-- Botón de Activar/Suspender -->
                                                                @if($mechanic->status == 0)
                                                                    {{-- Inactivo - Mostrar botón de activación --}}
                                                                    <a href="javascript:void(0)" 
                                                                       class="toggle-status" 
                                                                       data-id="{{ $mechanic->id }}"
                                                                       data-name="{{ $mechanic->full_name }}"
                                                                       data-action="activate"
                                                                       title="Activar">
                                                                        <i class="text-success ri-play-fill" style="font-size: 18px;"></i>
                                                                    </a>
                                                                @else
                                                                    {{-- Activo - Mostrar botón de suspensión --}}
                                                                    <a href="javascript:void(0)" 
                                                                       class="toggle-status" 
                                                                       data-id="{{ $mechanic->id }}"
                                                                       data-name="{{ $mechanic->full_name }}"
                                                                       data-action="suspend"
                                                                       title="Suspender">
                                                                        <i class="text-danger ri-pause-fill" style="font-size: 18px;"></i>
                                                                    </a>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                                                            <p class="mt-2">No hay Mecanico registrado</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div> {{-- Final de la tabla de mecanicos --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Creación -->
    <div class="modal fade" id="createMechancModal" tabindex="-1" aria-labelledby="createMechancModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #3c8749 0%, #23892a 100%);">
                    <h5 class="modal-title" style="color: #ffffff;" id="createMechancModalLabel">
                        Nuevo Mecánico
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createMechanicForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="document" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="document" name="document" placeholder="Ej: 123456789" required>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Ej: Juan Pérez" required>
                        </div>

                       <div class="row">
                        <div class="col-4 mb-3">
                            <label for="commission" class="form-label">Comisión %</label>
                            <input type="text" class="form-control" id="commission" name="commission" placeholder="Ej: 3.00">
                        </div>

                        <div class="col-8 mb-3">
                            <label for="phone" class="form-label">N° Celular</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Ej: 04120102034">
                        </div>
                       </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ej: tuemail@gmail.com" required>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="fw-semibold">Status</span>
                            <label class="switch sm-switch mb-0">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status" value="1" checked>
                                <span class="switch-state"></span>
                            </label>
                            <span id="statusText" class="text-success">ACTIVO</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnSave">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Final Modal de Creación -->

    <!-- Modal de Edición -->
    <div class="modal fade" id="editMechancModal" tabindex="-1" aria-labelledby="editMechancModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #ffd200 0%, #f7971e 100%);">
                     

                    <h5 class="modal-title" style="color: #000000;" id="editMechancModalLabel">
                        Editar Mecánico
                    </h5>
                    
                </div>
                <form id="editMechanicForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_document" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="edit_document" name="document" placeholder="Ej: 123456789" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_full_name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" placeholder="Ej: Juan Pérez" required>
                        </div>

                        <div class="row">
                            <div class="col-4 mb-3">
                                <label for="edit_commission" class="form-label">Comisión %</label>
                                <input type="text" class="form-control" id="edit_commission" name="commission" placeholder="Ej: 3101234567">
                            </div>
                            
                            <div class="col-8 mb-3">
                                <label for="edit_phone" class="form-label">N° Celular</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone" placeholder="Ej: 3101234567">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="edit_email" name="email" placeholder="Ej: tuemail@gmail.com" required>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="fw-semibold">Status</span>
                            <label class="switch sm-switch mb-0">
                                <input type="hidden" name="status" value="0" id="edit_status_hidden">
                                <input type="checkbox" name="status" id="edit_status" value="1">
                                <span class="switch-state"></span>
                            </label>
                            <span id="edit_status_text" class="text-success">ACTIVO</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning" id="btnUpdate">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Final Modal de Edición -->

@endsection

@push('styles')
    <style>
        /* Switch pequeño (versión SM) */
        .switch.sm-switch {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 22px;
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
            border-radius: 22px;
        }

        .switch.sm-switch .switch-state:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        .switch.sm-switch input:checked + .switch-state {
            background-color: #2ebe93;
        }
        
        .switch.sm-switch input:focus + .switch-state {
            box-shadow: 0 0 1px #2ebe93;
        }
        
        .switch.sm-switch input:checked + .switch-state:before {
            transform: translateX(20px);
        }

        .badge.bg-success {
            background-color: #2ebe93 !important;
        }
        .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        /* Estilo para el botón Actualizar */
        .btn-warning {
            color: #000;
            font-weight: 600;
        }
        .btn-warning:hover {
            color: #000;
            background: #e6a800;
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery (necesario para DataTable) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTable JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ======== SCRIPT 1: FUNCIONES UTILITARIAS ========= -->
    <script>
        $(document).ready(function() {
            // Función para actualizar el texto del status
            window.updateStatusText = function(checkbox, textElement, statusHidden) {
                if (checkbox.is(':checked')) {
                    textElement.text('ACTIVO').removeClass('text-danger').addClass('text-success');
                    if (statusHidden) statusHidden.val(1);
                } else {
                    textElement.text('INACTIVO').removeClass('text-success').addClass('text-danger');
                    if (statusHidden) statusHidden.val(0);
                }
            };

            // Función para recargar la página después de una acción
            window.reloadWithDelay = function(delay = 1500) {
                setTimeout(function() {
                    location.reload();
                }, delay);
            };

            // Función para mostrar notificación de éxito
            window.showSuccessNotification = function(title = "¡Éxito!", timer = 1500) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: title,
                    showConfirmButton: false,
                    timer: timer
                });
            };

            // Función para mostrar notificación de error
            window.showErrorNotification = function(title = "Error", text = "Ocurrió un error") {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text,
                    confirmButtonColor: '#dc3545'
                });
            };

            // Función para mostrar errores de validación
            window.showValidationErrors = function(errors) {
                var errorList = Object.values(errors).flat().join('\n');
                showErrorNotification('Error de validación', errorList);
            };
        });
    </script>

     <!-- ======== SCRIPT 2: DATATABLE Y FILTROS ========= -->
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            var table = $('#mechanicsTable').DataTable({
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

            // Filtro de estado - Versión simplificada que SI funciona
            $('#filterStatus').on('change', function() {
                var status = $(this).val();
                
                // Limpiar cualquier filtro existente
                table.column(4).search('').draw();
                
                if (status !== 'all') {
                    // Buscar el texto exacto en la columna
                    var searchText = status === '1' ? 'ACTIVO' : 'INACTIVO';
                    
                    // Usar búsqueda con expresión regular para coincidencia exacta
                    table.column(4).search('\\b' + searchText + '\\b', true, false).draw();
                }
            });

            // Guardar referencia de la tabla para usar en otros scripts
            window.mechanicsTable = table;
        });
    </script>

    <!-- =======  SCRIPT 3: SWITCH DE STATUS ======= -->
    <script>
        $(document).ready(function() {
            // Switch para creación
            $('#status').on('change', function() {
                updateStatusText($(this), $('#statusText'), null);
            });

            // Switch para edición
            $('#edit_status').on('change', function() {
                updateStatusText($(this), $('#edit_status_text'), $('#edit_status_hidden'));
            });
        });
    </script>

    <!-- =========  SCRIPT 4: CREAR NUEVO REGISTRO ========== -->
    <script>
        $(document).ready(function() {
            $('#createMechanicForm').on('submit', function(e) {
                e.preventDefault();
                
                var $btnSave = $('#btnSave');
                $btnSave.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');

                var formData = $(this).serialize();

                $.ajax({
                    url: '{{ route("mechanics.store") }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Resetear formulario
                        $('#createMechanicForm')[0].reset();
                        $('#status').prop('checked', true);
                        $('#statusText').text('ACTIVO').removeClass('text-danger').addClass('text-success');
                        
                        // Cerrar modal
                        $('#createMechancModal').modal('hide');
                        
                        // Mostrar notificación
                        showSuccessNotification('Registro exitoso..!');
                        
                        // Recargar página
                        reloadWithDelay();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if (errors) {
                            showValidationErrors(errors);
                        } else {
                            showErrorNotification('Error', 'Error al crear el mecánico.');
                        }
                        
                        $btnSave.prop('disabled', false).html('Guardar');
                    }
                });
            });

            // Resetear formulario al cerrar modal
            $('#createMechancModal').on('hidden.bs.modal', function() {
                $('#btnSave').prop('disabled', false).html('Guardar');
                $('#createMechanicForm')[0].reset();
                $('#status').prop('checked', true);
                $('#statusText').text('ACTIVO').removeClass('text-danger').addClass('text-success');
            });
        });
    </script>

    <!-- ======== SCRIPT 5: EDITAR REGISTRO  ============== -->
    <script>
        $(document).ready(function() {
            // Cargar datos para edición
            $(document).on('click', '.edit-mechanic', function() {
                var id = $(this).data('id');
                
                // Construir URL
                var url = "{{ route('mechanics.show', ':id') }}";
                url = url.replace(':id', id);
                
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
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        Swal.close();
                        
                        if (response.success) {
                            var mechanic = response.data;
                            
                            // Llenar campos
                            $('#edit_id').val(mechanic.id);
                            $('#edit_document').val(mechanic.document);
                            $('#edit_full_name').val(mechanic.full_name);
                            $('#edit_phone').val(mechanic.phone || '');
                            $('#edit_email').val(mechanic.email);
                            
                            // Configurar status
                            if (mechanic.status == 1) {
                                $('#edit_status').prop('checked', true);
                                $('#edit_status_text').text('ACTIVO').removeClass('text-danger').addClass('text-success');
                                $('#edit_status_hidden').val(1);
                            } else {
                                $('#edit_status').prop('checked', false);
                                $('#edit_status_text').text('INACTIVO').removeClass('text-success').addClass('text-danger');
                                $('#edit_status_hidden').val(0);
                            }
                        } else {
                            showErrorNotification('Error', response.message || 'No se pudieron cargar los datos');
                        }
                    },
                    error: function() {
                        Swal.close();
                        showErrorNotification('Error', 'No se pudieron cargar los datos del mecánico');
                    }
                });
            });

            // Actualizar registro
            $('#editMechanicForm').on('submit', function(e) {
                e.preventDefault();
                
                var id = $('#edit_id').val();
                
                // Construir URL
                var url = "{{ route('mechanics.update', ':id') }}";
                url = url.replace(':id', id);
                
                var $btnUpdate = $('#btnUpdate');
                $btnUpdate.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Actualizando...');

                var formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editMechancModal').modal('hide');
                        showSuccessNotification('¡Actualizado exitosamente!');
                        reloadWithDelay();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if (errors) {
                            showValidationErrors(errors);
                        } else {
                            showErrorNotification('Error', 'Error al actualizar el mecánico.');
                        }
                        
                        $btnUpdate.prop('disabled', false).html('Actualizar');
                    }
                });
            });

            // Resetear formulario al cerrar modal
            $('#editMechancModal').on('hidden.bs.modal', function() {
                $('#btnUpdate').prop('disabled', false).html('Actualizar');
                $('#editMechanicForm')[0].reset();
            });
        });
    </script>

    <!-- ========== SCRIPT 6: ELIMINAR REGISTRO =========== -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-mechanic', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');
                
                // Construir URL
                var url = "{{ route('mechanics.destroy', ':id') }}";
                url = url.replace(':id', id);
                
                Swal.fire({
                    title: '¿Eliminar mecánico?',
                    text: 'El mecánico "' + name + '" será eliminado. ¿Estás seguro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: 'Mecánico eliminado exitosamente.',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                showErrorNotification('Error', 'Imposible eliminar el mecánico');
                            }
                        });
                    }
                });
            });
        });
    </script>

    <!-- ========== SCRIPT 7: CAMBIAR ESTADO (ACTIVAR/SUSPENDER) ========= -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.toggle-status', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');
                var action = $(this).data('action');
                
                // Construir URL
                var url = "{{ route('mechanics.toggle-status', ':id') }}";
                url = url.replace(':id', id);
                
                // Determinar mensajes según la acción
                var title = action === 'activate' ? '¿Activar mecánico?' : '¿Suspender mecánico?';
                var text = action === 'activate' 
                    ? 'El mecánico "' + name + '" será activado. ¿Estás seguro?' 
                    : 'El mecánico "' + name + '" será suspendido. ¿Estás seguro?';
                var confirmText = action === 'activate' ? 'Sí, activar' : 'Sí, suspender';
                var icon = action === 'activate' ? 'success' : 'warning';
                
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: action === 'activate' ? '#28a745' : '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'PATCH',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                var successMessage = action === 'activate' 
                                    ? 'Mecánico activado exitosamente.' 
                                    : 'Mecánico suspendido exitosamente.';
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Completado!',
                                    text: successMessage,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                var errorMsg = 'No se pudo cambiar el estado del mecánico.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <!-- ========== SCRIPT 7: MENSAJES DE SESSION ========= -->
    <script>
        $(document).ready(function() {
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

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>

@endpush