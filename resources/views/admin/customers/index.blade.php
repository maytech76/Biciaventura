@extends('admin.layouts.master')

@section('title', 'Clientes')

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

                            {{-- Start Table --}} 
                            <div class="card card-table">
                                <div class="card-body">
                                    
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-3">
                                        <h3 class="fw-bold">Listado de Clientes</h3>
                                        <div class="d-flex gap-2">
                                            <!-- Filtro de estado -->
                                            <select class="form-select" id="filterStatus" style="width: auto;">
                                                <option value="all">Todos los estados</option>
                                                <option value="1">Activos</option>
                                                <option value="0">Inactivos</option>
                                            </select>
                                            
                                            <form class="d-inline-flex">
                                                <a href="javascript:void(0)" 
                                                    class="align-items-center btn btn-theme d-flex" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#createCustomerModal">
                                                    <i data-feather="plus-square"></i>+ Nuevo
                                                </a>
                                            </form>
                                        </div>
                                    </div>

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <div class="table-responsive" style="overflow-x: visible !important;">
                                        <table class="table table-striped table-hover" id="customersTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="font-size: 14px;">Doc</th>
                                                    <th style="font-size: 14px;">Nombre</th>
                                                    <th style="font-size: 14px;">phone</th>
                                                    <th style="font-size: 14px;">Email</th>
                                                    <th style="font-size: 14px;">Ciudad</th>
                                                    <th style="font-size: 14px;">Status</th>
                                                    <th style="font-size: 14px;">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($users as $user)
                                                <tr>
                                                    <td class="text-start">{{ $user->document }}</td>
                                                    <td class="text-start">{{ $user->name }}</td>
                                                    <td class="text-start">{{ $user->phone }}</td>
                                                    <td class="text-start">{{ $user->email }}</td>
                                                    <td class="text-start">{{ $user->city->name ?? 'N/A' }}</td>
                                                    <td class="text-start" style="color: {{ $user->status == 1 ? 'green' : 'red' }};">
                                                        {{ $user->status == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                    </td>
                                                    <td>
                                                        <ul class="list-unstyled d-flex gap-2 mb-0">
                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                    class="text-warning edit-customer" 
                                                                    data-id="{{ $user->id }}"
                                                                    data-document="{{ $user->document }}"
                                                                    data-name="{{ $user->name }}"
                                                                    data-phone="{{ $user->phone }}"
                                                                    data-email="{{ $user->email }}"
                                                                    data-city_id="{{ $user->city_id }}"
                                                                    data-status="{{ $user->status }}">
                                                                        <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                   class="delete-customer" 
                                                                   data-id="{{ $user->id }}"
                                                                   data-name="{{ $user->name }}"
                                                                   data-bs-toggle="modal" 
                                                                   data-bs-target="#deleteModal{{ $user->id }}">
                                                                    <i class="ri-user-unfollow-line" style="color: #a72b38"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   

                                                <!-- Modal de Eliminación/Desactivación -->
                                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background: linear-gradient(135deg, #ec99a2 0%, #a72b38 100%); color: white; border-bottom: none;">
                                                                <h5 class="modal-title" style="color: white;" id="deleteModalLabel{{ $user->id }}">
                                                                    <i class="ri-alert-line me-2"></i>Confirmar Desactivación
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center mb-3">
                                                                    <i class="ri-error-warning-line" style="font-size: 48px; color: #dc3545;"></i>
                                                                </div>
                                                                <p class="text-center fs-6">
                                                                    ¿Estás seguro de que quieres desactivar al cliente?
                                                                </p>
                                                                <p class="text-center fw-bold text-danger" style="font-size: 1.1rem;">
                                                                    "{{ $user->name }}"
                                                                </p>
                                                                <div class="alert alert-warning mt-3" role="alert">
                                                                    <i class="ri-information-line me-2"></i>
                                                                    <strong>¡Importante!</strong> El cliente será desactivado (Status = INACTIVO) pero sus datos permanecerán en el sistema.
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn" style="background-color: #6c757d; color: white; border: none; border-radius: 0.375rem;" data-bs-dismiss="modal">
                                                                    <i class="ri-close-line me-1"></i>Cancelar
                                                                </button>
                                                                <form action="{{ route('customers.destroy', $user->id) }}" method="POST" id="deleteForm{{ $user->id }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn" style="background-color: #dc3545; color: white; border: none; border-radius: 0.375rem;" id="deleteBtn{{ $user->id }}">
                                                                        <i class="ri-user-unfollow-line me-1"></i>
                                                                        <span id="deleteBtnText{{ $user->id }}">Desactivar</span>
                                                                        <span id="deleteBtnSpinner{{ $user->id }}" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                                                            <p class="mt-2">No hay Clientes registrados</p>
                                                            <a href="javascript:void(0)" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                                                                <i class="ri-add-line"></i> Registrar al primer Cliente
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>           
                                </div>
                            </div> {{-- end table --}}

                            <!-- Modal para Crear Nuevo Cliente -->
                            <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: linear-gradient(135deg, #3c8749 0%, #23892a 100%); color: white; border-bottom: none;">
                                            <h5 class="modal-title" style="color: white;" id="createCustomerModalLabel">
                                                Registro de Nuevo Cliente
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <form action="{{ route('customers.store') }}" method="POST" id="createCustomerForm" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                <div class="row">
                                                    {{-- Documento --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Cedula Identidad <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                            class="form-control border" 
                                                            id="document" 
                                                            name="document" 
                                                            placeholder="Ej: 12345678" 
                                                            required>
                                                        <div class="invalid-feedback" id="document-error"></div>
                                                    </div>

                                                    {{-- Email --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                                        <input type="email" 
                                                            class="form-control border" 
                                                            id="email" 
                                                            name="email" 
                                                            placeholder="Ej: Cliente@email.com" 
                                                            required>
                                                        <div class="invalid-feedback" id="email-error"></div>
                                                    </div>

                                                    {{-- Nombre --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Nombre Completo<span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                            class="form-control border" 
                                                            id="name" 
                                                            name="name" 
                                                            placeholder="Ej: Juan Carlos Castillo Cerpa" 
                                                            required>
                                                        <div class="invalid-feedback" id="name-error"></div>
                                                    </div>

                                                    {{-- phone --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">phone <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control border" 
                                                               id="phone" 
                                                               name="phone" 
                                                               placeholder="Ej: 3001234567"
                                                               required>
                                                        <div class="invalid-feedback" id="phone-error"></div>
                                                    </div>

                                                    {{-- Ciudad --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Ciudad</label>
                                                        <select class="form-select border" id="city_id" name="city_id">
                                                            <option value="">Seleccione Ciudad</option>
                                                            @foreach($cities as $city)
                                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback" id="city_id-error"></div>
                                                    </div>

                                                    {{-- Status --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                                        <select class="form-select border" id="status" name="status" required>
                                                            <option value="1">Activo</option>
                                                            <option value="0">Inactivo</option>
                                                        </select>
                                                        <div class="invalid-feedback" id="status-error"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer" style="border-top: 1px solid #dee2e6;">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-theme" id="btnSubmit">
                                                    <span id="btnText">Registrar</span>
                                                    <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> {{-- Final Modal nuevo Cliente --}}

                            <!-- Modal para Editar Cliente -->
                            <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: linear-gradient(135deg, #f0ad4e 0%, #d58512 100%); color: white; border-bottom: none;">
                                            <h5 class="modal-title" style="color: white;" id="editCustomerModalLabel">
                                                Editar Cliente
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <form action="" method="POST" id="editCustomerForm" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                <div class="row">
                                                    {{-- ID Oculto --}}
                                                    <input type="hidden" id="edit_id" name="id">

                                                    {{-- Documento --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Cedula Identidad <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                            class="form-control border" 
                                                            id="edit_document" 
                                                            name="document" 
                                                            placeholder="Ej: 12345678" 
                                                            required>
                                                        <div class="invalid-feedback" id="edit_document-error"></div>
                                                    </div>

                                                    {{-- Email --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                                        <input type="email" 
                                                            class="form-control border" 
                                                            id="edit_email" 
                                                            name="email" 
                                                            placeholder="Ej: Cliente@email.com" 
                                                            required>
                                                        <div class="invalid-feedback" id="edit_email-error"></div>
                                                    </div>

                                                    {{-- Nombre --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                            class="form-control border" 
                                                            id="edit_name" 
                                                            name="name" 
                                                            placeholder="Ej: Juan Carlos Castillo Cerpa" 
                                                            required>
                                                        <div class="invalid-feedback" id="edit_name-error"></div>
                                                    </div>

                                                    {{-- phone --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">phone <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control border" 
                                                               id="edit_phone" 
                                                               name="phone" 
                                                               placeholder="Ej: 3001234567"
                                                               required>
                                                        <div class="invalid-feedback" id="edit_phone-error"></div>
                                                    </div>

                                                    {{-- Ciudad --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Ciudad</label>
                                                        <select class="form-select border" id="edit_city_id" name="city_id">
                                                            <option value="">Seleccione Ciudad</option>
                                                            @foreach($cities as $city)
                                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback" id="edit_city_id-error"></div>
                                                    </div>

                                                    {{-- Status --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                                        <select class="form-select border" id="edit_status" name="status" required>
                                                            <option value="1">Activo</option>
                                                            <option value="0">Inactivo</option>
                                                        </select>
                                                        <div class="invalid-feedback" id="edit_status-error"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer" style="border-top: 1px solid #dee2e6;">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-warning" id="btnEditSubmit">
                                                    <span id="btnEditText">Actualizar</span>
                                                    <span id="btnEditSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> {{-- Final Modal Editar Cliente --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ======= SCRIPT PARA REGISTRO Y DATATABLE ========= --}}
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            var table = $('#customersTable').DataTable({
                responsive: true,
                autoWidth: false,
                scrollX: false, // Desactivar scroll horizontal
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                columnDefs: [
                    {
                        targets: 6,
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[1, 'asc']],
                pageLength: 5,  // ✅ Cambiado a 5 registros
                lengthMenu: [
                    [5, 10, 25, 50, -1],  // Opciones disponibles
                    [5, 10, 25, 50, "Todos"]  // Texto a mostrar
                ],
               
            });

            // Manejo del formulario de creación
            $('#createCustomerForm').on('submit', function(e) {
                $('#btnText').text('Registrando...');
                $('#btnSpinner').removeClass('d-none');
                $('#btnSubmit').prop('disabled', true);
            });

            // Resetear formulario al cerrar el modal de creación
            $('#createCustomerModal').on('hidden.bs.modal', function() {
                $('#createCustomerForm')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#btnText').text('Registrar');
                $('#btnSpinner').addClass('d-none');
                $('#btnSubmit').prop('disabled', false);
            });

            // SweetAlert para éxito
            @if(session('success'))
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif

            // SweetAlert para error
            @if(session('error'))
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "{{ session('error') }}",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Entendido"
                });
            @endif

            // Manejar errores de validación en creación
            @if($errors->any() && !session('edit_error'))
                $('#btnText').text('Registrar');
                $('#btnSpinner').addClass('d-none');
                $('#btnSubmit').prop('disabled', false);
                
                @foreach($errors->all() as $error)
                    Swal.fire({
                        icon: "error",
                        title: "Error de validación",
                        text: "{{ $error }}",
                        confirmButtonColor: "#d33",
                        confirmButtonText: "Entendido"
                    });
                @break
                @endforeach
            @endif
        });
    </script>

   
    {{-- =======  SCRIPT PARA EDITAR REGISTRO ======== --}}
    <script>
        $(document).ready(function() {
            // Cuando se hace clic en el botón de editar
            $(document).on('click', '.edit-customer', function(e) {
                e.preventDefault();
                
                // Obtener los datos del cliente
                var id = $(this).data('id');
                var document = $(this).data('document');
                var name = $(this).data('name');
                var phone = $(this).data('phone');
                var email = $(this).data('email');
                var city_id = $(this).data('city_id');
                var status = $(this).data('status');

                // Construir la URL para el formulario de edición
                var url = "{{ route('customers.update', ':id') }}";
                url = url.replace(':id', id);
                
                // Actualizar el action del formulario
                $('#editCustomerForm').attr('action', url);
                
                // Llenar los campos del formulario
                $('#edit_id').val(id);
                $('#edit_document').val(document);
                $('#edit_name').val(name);
                $('#edit_phone').val(phone);
                $('#edit_email').val(email);
                $('#edit_city_id').val(city_id);
                $('#edit_status').val(status);
                
                // Limpiar mensajes de error previos
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Mostrar el modal
                $('#editCustomerModal').modal('show');
            });

            // Manejar el envío del formulario de edición
            $('#editCustomerForm').on('submit', function(e) {
                // Mostrar loading en el botón
                $('#btnEditText').text('Actualizando...');
                $('#btnEditSpinner').removeClass('d-none');
                $('#btnEditSubmit').prop('disabled', true);
                
                // El formulario se enviará normalmente
            });

            // Resetear formulario al cerrar el modal de edición
            $('#editCustomerModal').on('hidden.bs.modal', function() {
                $('#editCustomerForm')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#btnEditText').text('Actualizar');
                $('#btnEditSpinner').addClass('d-none');
                $('#btnEditSubmit').prop('disabled', false);
                $('#editCustomerForm').attr('action', ''); // Limpiar action
            });

            // Manejar errores de validación en edición
            @if($errors->any() && session('edit_error'))
                $('#btnEditText').text('Actualizar');
                $('#btnEditSpinner').addClass('d-none');
                $('#btnEditSubmit').prop('disabled', false);
                
                // Mostrar errores en el modal
                var errorMessages = '';
                @foreach($errors->all() as $error)
                    errorMessages += '• {{ $error }}\n';
                @endforeach
                
                Swal.fire({
                    icon: "error",
                    title: "Error de validación",
                    text: errorMessages,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Entendido"
                });
                
                // Abrir el modal de edición nuevamente
                $('#editCustomerModal').modal('show');
            @endif
        });
    </script>

  
    {{-- =======  SCRIPT PARA FILTRAR POR ESTADO (ALTERNATIVO) ========= --}}
    <script>
        $(document).ready(function() {
            // Filtro por estado
            $('#filterStatus').on('change', function() {
                var status = $(this).val();
                var table = $('#customersTable').DataTable();
                
                if (status === 'all') {
                    table.column(5).search('').draw();
                } else {
                    // Buscar el valor numérico en la columna de status
                    // Asumiendo que la columna 5 contiene "ACTIVO" o "INACTIVO"
                    var searchText = status === '1' ? 'ACTIVO' : 'INACTIVO';
                    
                    // Usar filtro personalizado para mayor control
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            var statusColumn = data[5]; // Columna de status
                            if (status === 'all') return true;
                            return statusColumn === searchText;
                        }
                    );
                    
                    table.draw();
                    
                    // Remover el filtro personalizado después de usarlo
                    $.fn.dataTable.ext.search.pop();
                }
            });
        });
    </script>

    {{-- =======  SCRIPT PARA VALIDACIÓN DE MAYÚSCULAS/MINÚSCULAS ======== --}}
    <script>
        $(document).ready(function() {
            /**
             * Función para convertir texto a mayúsculas
             */
            function toUpperCase(input) {
                if (input) {
                    input.value = input.value.toUpperCase();
                }
            }

            /**
             * Función para convertir texto a minúsculas
             */
            function toLowerCase(input) {
                if (input) {
                    input.value = input.value.toLowerCase();
                }
            }

            // =============================================
            // VALIDACIONES PARA MODAL DE CREACIÓN
            // =============================================
            
            // Convertir nombre a mayúsculas mientras se escribe
            $('#createCustomerForm #name').on('input', function() {
                toUpperCase(this);
            });

            // Convertir email a minúsculas mientras se escribe
            $('#createCustomerForm #email').on('input', function() {
                toLowerCase(this);
            });

            // Validar al perder el foco (blur) en nombre
            $('#createCustomerForm #name').on('blur', function() {
                toUpperCase(this);
            });

            // Validar al perder el foco (blur) en email
            $('#createCustomerForm #email').on('blur', function() {
                toLowerCase(this);
            });

            // =============================================
            // VALIDACIONES PARA MODAL DE EDICIÓN
            // =============================================
            
            // Convertir nombre a mayúsculas mientras se escribe
            $('#editCustomerForm #edit_name').on('input', function() {
                toUpperCase(this);
            });

            // Convertir email a minúsculas mientras se escribe
            $('#editCustomerForm #edit_email').on('input', function() {
                toLowerCase(this);
            });

            // Validar al perder el foco (blur) en nombre
            $('#editCustomerForm #edit_name').on('blur', function() {
                toUpperCase(this);
            });

            // Validar al perder el foco (blur) en email
            $('#editCustomerForm #edit_email').on('blur', function() {
                toLowerCase(this);
            });

            // =============================================
            // VALIDACIÓN ANTES DE ENVIAR EL FORMULARIO
            // =============================================
            
            // Validar creación antes de enviar
            $('#createCustomerForm').on('submit', function(e) {
                var nameInput = $('#createCustomerForm #name');
                var emailInput = $('#createCustomerForm #email');
                
                // Asegurar mayúsculas en nombre antes de enviar
                if (nameInput.val()) {
                    nameInput.val(nameInput.val().toUpperCase());
                }
                
                // Asegurar minúsculas en email antes de enviar
                if (emailInput.val()) {
                    emailInput.val(emailInput.val().toLowerCase());
                }
            });

            // Validar edición antes de enviar
            $('#editCustomerForm').on('submit', function(e) {
                var nameInput = $('#editCustomerForm #edit_name');
                var emailInput = $('#editCustomerForm #edit_email');
                
                // Asegurar mayúsculas en nombre antes de enviar
                if (nameInput.val()) {
                    nameInput.val(nameInput.val().toUpperCase());
                }
                
                // Asegurar minúsculas en email antes de enviar
                if (emailInput.val()) {
                    emailInput.val(emailInput.val().toLowerCase());
                }
            });

            // =============================================
            // VALIDACIÓN AL ABRIR EL MODAL DE EDICIÓN
            // =============================================
            
            // Cuando se carga el modal de edición, asegurar formato correcto
            $(document).on('shown.bs.modal', '#editCustomerModal', function() {
                var nameInput = $('#editCustomerForm #edit_name');
                var emailInput = $('#editCustomerForm #edit_email');
                
                if (nameInput.val()) {
                    nameInput.val(nameInput.val().toUpperCase());
                }
                
                if (emailInput.val()) {
                    emailInput.val(emailInput.val().toLowerCase());
                }
            });

            // =============================================
            // VALIDACIÓN AL ABRIR EL MODAL DE CREACIÓN
            // =============================================
            
            // Cuando se abre el modal de creación, limpiar y preparar
            $(document).on('shown.bs.modal', '#createCustomerModal', function() {
                var nameInput = $('#createCustomerForm #name');
                var emailInput = $('#createCustomerForm #email');
                
                // Si hay valores por defecto, formatearlos
                if (nameInput.val()) {
                    nameInput.val(nameInput.val().toUpperCase());
                }
                
                if (emailInput.val()) {
                    emailInput.val(emailInput.val().toLowerCase());
                }
            });

            console.log('✅ Validaciones de mayúsculas/minúsculas activadas');
        });
    </script>

@endpush