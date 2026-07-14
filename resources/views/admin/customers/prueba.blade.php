@extends('admin.layouts.master')

@section('title', 'Clientes')

@section('styles')
    <!-- DataTable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Eliminar scroll horizontal */
        .dataTables_wrapper .dataTables_scroll {
            overflow: visible !important;
        }
        
        .dataTables_wrapper .dataTables_scrollHead,
        .dataTables_wrapper .dataTables_scrollBody {
            overflow: visible !important;
        }
        
        .table-responsive {
            overflow-x: visible !important;
        }
        
        /* Paginación */
        .dataTables_wrapper .dataTables_paginate {
            margin: 0 !important;
            padding: 0 !important;
            float: right !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin: 0 2px !important;
            padding: 0.375rem 0.75rem !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.25rem !important;
            background: #fff !important;
            color: #333 !important;
            font-size: 0.875rem !important;
            line-height: 1 !important;
            transition: all 0.2s ease !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0d6efd !important;
            color: #fff !important;
            border-color: #0d6efd !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef !important;
            color: #333 !important;
            border-color: #dee2e6 !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #0b5ed7 !important;
            color: #fff !important;
            border-color: #0a58ca !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            background: #f8f9fa !important;
        }
        
        .dataTables_wrapper .dataTables_info {
            padding-top: 0.5rem !important;
            font-size: 0.875rem !important;
            color: #6c757d !important;
        }
        
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 0.5rem !important;
        }
        
        @media (max-width: 576px) {
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.75rem !important;
                margin: 0 1px !important;
            }
            
            .dataTables_wrapper .dataTables_info {
                font-size: 0.75rem !important;
            }
        }
    </style>
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
                                        <form class="d-inline-flex">
                                            <a href="javascript:void(0)" 
                                                class="align-items-center btn btn-theme d-flex" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#createCustomerModal">
                                                <i data-feather="plus-square"></i>+ Nuevo
                                            </a>
                                        </form>
                                    </div>

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <div class="table-responsive category-table">
                                        <table class="table table-striped table-hover" id="customersTable">
                                            <thead>
                                                <tr>
                                                    <th>Doc</th>
                                                    <th>Nombre</th>
                                                    <th>Celular</th>
                                                    <th>Email</th>
                                                    <th>Ciudad</th>
                                                    <th>Status</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($customers as $customer)
                                                <tr>
                                                    <td class="text-start">{{ $customer->document }}</td>
                                                    <td class="text-start">{{ $customer->full_name }}</td>
                                                    <td class="text-start">{{ $customer->celular }}</td>
                                                    <td class="text-start">{{ $customer->email }}</td>
                                                    <td class="text-start">{{ $customer->city->name ?? 'N/A' }}</td>
                                                    <td class="text-start" style="color: {{ $customer->status == 1 ? 'green' : 'red' }};">
                                                        {{ $customer->status == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                    </td>
                                                    <td>
                                                        <ul class="list-unstyled d-flex gap-2 mb-0">
                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                    class="text-warning edit-customer" 
                                                                    data-id="{{ $customer->id }}"
                                                                    data-document="{{ $customer->document }}"
                                                                    data-full_name="{{ $customer->full_name }}"
                                                                    data-celular="{{ $customer->celular }}"
                                                                    data-email="{{ $customer->email }}"
                                                                    data-city_id="{{ $customer->city_id }}"
                                                                    data-status="{{ $customer->status }}">
                                                                        <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $customer->id }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   

                                                <!-- Modal de Eliminación -->
                                                <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background: linear-gradient(135deg, #ec99a2 0%, #a72b38 100%); color: white; border-bottom: none;">
                                                                <h5 class="modal-title" style="color: white;" id="deleteModalLabel">Confirmar Eliminación</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que quieres eliminar el Cliente <strong>"{{ $customer->full_name }}"</strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn" style="background-color: #6c757d; color: white; border: none; border-radius: 0.375rem;" data-bs-dismiss="modal">
                                                                    Cancelar
                                                                </button>
                                                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn" style="background-color: #bc1a1a; color: white; border: none; border-radius: 0.375rem;">
                                                                        Eliminar
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
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: linear-gradient(135deg, #3c8749 0%, #23892a 100%); color: white; border-bottom: none;">
                                            <h5 class="modal-title" style="color: white;" id="createCustomerModalLabel">
                                                Registro de Nuevo Cliente
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <form id="createCustomerForm">
                                            @csrf
                                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                <div class="row">
                                                    {{-- Documento --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Cédula Identidad <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control border" 
                                                               id="document" 
                                                               name="document" 
                                                               placeholder="Ej: 1234567" 
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
                                                               placeholder="Ej: cliente@email.com" 
                                                               required>
                                                        <div class="invalid-feedback" id="email-error"></div>
                                                    </div>

                                                    {{-- Nombre Completo --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control border" 
                                                               id="full_name" 
                                                               name="full_name" 
                                                               placeholder="Ej: Juan Carlos Pérez" 
                                                               required>
                                                        <div class="invalid-feedback" id="full_name-error"></div>
                                                    </div>

                                                    {{-- Celular --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Celular <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control border" 
                                                               id="celular" 
                                                               name="celular" 
                                                               placeholder="Ej: 3001234567"
                                                               required>
                                                        <div class="invalid-feedback" id="celular-error"></div>
                                                    </div>

                                                    {{-- Ciudad --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Ciudad</label>
                                                        <select class="form-select border" id="city_id" name="city_id">
                                                            <option value="">Seleccione una Ciudad</option>
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
    
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            var table = $('#customersTable').DataTable({
                responsive: true,
                autoWidth: false,
                scrollX: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                columnDefs: [
                    {
                        targets: 6, // Columna de opciones
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[1, 'asc']], // Ordenar por nombre
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
            });

            // Manejar el envío del formulario
            $('#createCustomerForm').on('submit', function(e) {
                e.preventDefault();
                
                // Mostrar loading en el botón
                $('#btnText').text('Registrando...');
                $('#btnSpinner').removeClass('d-none');
                $('#btnSubmit').prop('disabled', true);
                
                // Limpiar errores anteriores
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Crear FormData para enviar los datos
                var formData = new FormData(this);
                
                $.ajax({
                    url: "{{ route('customers.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            // Cerrar modal
                            $('#createCustomerModal').modal('hide');
                            
                            // Resetear formulario
                            $('#createCustomerForm')[0].reset();
                            
                            // Mostrar SweetAlert2 de éxito
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Registro de cliente exitoso...!",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            // Recargar la página para ver el nuevo registro
                            setTimeout(function() {
                                location.reload();
                            }, 1600);
                        }
                    },
                    error: function(xhr) {
                        // Restaurar botón
                        $('#btnText').text('Registrar');
                        $('#btnSpinner').addClass('d-none');
                        $('#btnSubmit').prop('disabled', false);
                        
                        if (xhr.status === 422) {
                            // Errores de validación
                            var errors = xhr.responseJSON.errors;
                            
                            $.each(errors, function(key, value) {
                                var input = $('#' + key);
                                var errorDiv = $('#' + key + '-error');
                                
                                input.addClass('is-invalid');
                                errorDiv.text(value[0]);
                            });
                            
                            // Mostrar SweetAlert de error
                            Swal.fire({
                                icon: "error",
                                title: "Error de validación",
                                text: "Por favor, revisa los campos marcados en rojo.",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "Entendido"
                            });
                        } else {
                            // Error general
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Ocurrió un error al registrar el cliente. Intenta nuevamente.",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "Entendido"
                            });
                        }
                    }
                });
            });

            // Resetear formulario al cerrar el modal
            $('#createCustomerModal').on('hidden.bs.modal', function() {
                $('#createCustomerForm')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#btnText').text('Registrar');
                $('#btnSpinner').addClass('d-none');
                $('#btnSubmit').prop('disabled', false);
            });
        });
    </script>
@endpush