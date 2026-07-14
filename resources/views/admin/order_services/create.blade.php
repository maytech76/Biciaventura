@extends('admin.layouts.master')

@section('title', 'ordenes de Servicio')

@section('content')
    <div class="compact-wrapper">
        <div class="page-body-wrapper">
            <div class="page-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table">
                                <div class="card-body">
                                    <form action="{{ route('order-services.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-3">
                                            <h4 class="fw-bold">Orden de Servicio</h4>
                                            <div class="d-flex gap-2">
                                               <div class="d-flex align-items-center" style="margin-right: 4rem; color: rgb(56, 163, 6);">
                                                <i class="fas fa-coffee me-2"></i> <h4> 760,00 Bs</h4>
                                               </div>
                                                <div style="margin-right: 4rem">
                                                 <h4 style="color: orangered">Fecha: {{ date('d-m-Y') }}</h4>
                                                </div>
                                                <h3 class="fw-bold" style="color: rgb(0, 4, 128);">N°:</h3>
                                                <h4 style="color: rgb(0, 4, 128)">ODS-00001</h4>
                                            </div>
                                        </div>

                                            {{-- cliente / bicicleta --}}
                                            <div class="card mb-2">
                                                <div class="row justify-content-between g-2">
                                                
                                                    {{-- Seleccionar Cliente --}}
                                                    <div class="card col-sm-12 col-md-6 border border-secondary" style="border-width: 1px !important; border-color:rgb(239, 239, 244) !important; padding-bottom: 0.5rem;">
                                                        <label for="user_id" class="form-label fw-bold mt-2">Cliente</label>
                                                        <div class="row g-1 align-items-center">
                                                            <div class="col-md-6">
                                                                <select class="js-example-basic-single border border-secondary select2" name="user_id" id="user_id" style="width: 100%;">
                                                                    <option value="">Seleccione un cliente</option>
                                                                    @foreach ($users as $user)
                                                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                            {{ $user->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('user_id')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="col-6">
                                                                <button type="button" class="btn" style="background-color: rgb(156, 229, 156); color:#013f0c">+ Nuevo</button>
                                                            </div>
                                                        </div>
                                                        
                                                         {{-- Informacion del cliente --}}
                                                        <div class="mt-2">
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h6 class="fw-bold mb-0" style="min-width: 100px; font-size: 0.80rem;">Nombre:</h6>
                                                                <p class="mb-0" id="user-name" style="font-size: 0.70rem">-</p>
                                                            </div>
                                                        
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h6 class="fw-bold mb-0" style="min-width: 100px; font-size: 0.80rem;">Teléfono:</h6>
                                                                <p class="mb-0" id="user-phone" style="font-size: 0.70rem">-</p>
                                                            </div>
                                                        
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h6 class="fw-bold mb-0" style="min-width: 100px; font-size: 0.80rem;">Ciudad:</h6>
                                                                <p class="mb-0" id="user-city" style="font-size: 0.70rem">-</p>
                                                            </div>
                                                        
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h6 class="fw-bold mb-0" style="min-width: 100px; font-size: 0.80rem;">Correo:</h6>
                                                                <p class="mb-0" id="user-email" style="font-size: 0.70rem">-</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Seleccionar Vehiculos --}}
                                                    <div class="card col-sm-12 col-md-5 border border-secondary" style="border-width: 1px !important; border-color:rgb(239, 239, 233) !important; padding-bottom: 0.5rem;">
                                                        <label for="vehicle_id" class="form-label fw-bold mt-2">Bicicleta</label>
                                                        
                                                        <div class="row g-1 align-items-center">
                                                            <div class="col-6">
                                                                <select class="js-example-basic-single border border-secondary" name="vehicle_id" id="vehicle_id" style="width: 100%;">
                                                                    <option value="">Listado de bicicletas</option>
                                                                    @if(isset($vehicles) && old('vehicle_id'))
                                                                        @foreach ($vehicles as $vehicle)
                                                                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                                                {{ $vehicle->model }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                @error('vehicle_id')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="col-6">
                                                                <button type="button" class="btn" style="background-color: rgb(156, 229, 156); color:#013f0c">+ Nuevo</button>
                                                            </div>
                                                        </div>

                                                        <div class="mt-2 vehicle-info">
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h5 class="fw-bold mb-0" style="min-width: 140px; font-size: 0.80rem;">Tipo:</h5>
                                                                <p class="mb-0" id="vehicle-type" style="font-size: 0.70rem">-</p>
                                                            </div>
                                                        
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h5 class="fw-bold mb-0" style="min-width: 140px; font-size: 0.80rem;">Marca:</h5>
                                                                <p class="mb-0" id="vehicle-brand" style="font-size: 0.70rem">-</p>
                                                            </div>
                                                        
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h5 class="fw-bold mb-0" style="min-width: 140px; font-size: 0.80rem;">Modelo:</h5>
                                                                <p class="mb-0" id="vehicle-model" style="font-size: 0.70rem">-</p>
                                                            </div>
                                                        
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <h5 class="fw-bold mb-0" style="min-width: 140px; font-size: 0.80rem;">Fecha de Registro:</h5>
                                                                <p class="mb-0" id="vehicle-created" style="font-size: 0.70rem">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            {{-- Tabla y detalles de la orden de servicio --}}
                                            <div class="card items_table">
                                            {{-- Fotos y detalles de la orden de servicio --}}
                                            <div class="row justify-content-start align-items-center g-3 mb-3">
                                                <div class="col-lg-2 col-sm-12 text-lg-start text-sm-start">
                                                    <label for="imagenes" class="form-label fw-bold mb-0">Fotos y Detalles :</label>
                                                </div>
                                                <div class="col-lg-6 col-sm-12">
                                                    <input class="form-control" type="file" name="imagenes[]" id="imagenes" multiple accept="image/*">
                                                    @error('imagenes')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <small class="text-danger">Puedes cargar varias imagenes (formatos: jpeg, png, jpg).</small> 
                                                </div>
                                            </div>

                                            {{-- Productos / Servicios --}}
                                            <div class="row valores_items mb-2">
                                                {{-- seleccionar producto --}}
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold w-100" style="font-size: 16px;">Productos / Servicios</label>
                                                    <select class="js-example-basic-single border border-secondary" name="product_id" id="select_prod" style="width: 100% !important;">
                                                        <option value="">Seleccionar productos ó servicios</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-1" style="margin-left: 1rem">
                                                    <label for="quantity" class="form-label fw-bold" style="font-size: 16px">Cant</label>
                                                    <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1">
                                                </div>
                                                
                                                <div class="col-2">
                                                    <label for="unit_price" class="form-label fw-bold" style="font-size: 16px">Precio Unit</label>
                                                    <input type="number" step="0.01" class="form-control" name="unit_price" id="unit_price" min="0">
                                                </div>
                                            
                                                <div class="col-2">
                                                    <label for="discount" class="form-label fw-bold" style="font-size: 16px">Descuento</label>
                                                    <input type="number" step="0.01" class="form-control" name="discount" id="discount" min="0" value="0">
                                                </div>

                                                <div class="col-2">
                                                    <label for="subtotal_display" class="form-label fw-bold" style="font-size: 16px">Sub-Total</label>
                                                    <input type="text" class="form-control" name="subtotal_display" id="subtotal_display" readonly style="background-color: #f8f9fa; font-weight: bold;">
                                                </div>
                                                
                                                <div class="col-1">
                                                    <label class="mb-1">&nbsp;</label>
                                                    <button type="button" id="btnAddItem" class="btn form-control mt-2 gap-1" style="font-size:15px; background-color:rgb(22, 195, 154); color:rgb(236, 244, 236);">
                                                        <i class="fa fa-arrow-down"></i> Add
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Tabla orderservicesTable --}}
                                            <div class="table-responsive" style="overflow-x: visible !important;">
                                                <table class="table table-striped table-hover" id="orderservicesTable" style="width:100%">
                                                     {{-- Encabezado-tabla --}}
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 16px;">PRODUCTOS / SERVICIOS</th>
                                                            <th style="font-size: 16px;">CANTIDAD</th>
                                                            <th style="font-size: 16px;text-align: right">PRECIO UNITARIO</th>
                                                            <th style="font-size: 16px;text-align: right">SUB-TOTAL</th>
                                                            <th class="" style="font-size: 16px;text-align: center">OPCIONES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- filas de la tabla --}}
                                                        <tr>
                                                            <td class="text-start">
                                                                <p class="text-md" style="font-size:16px;"> </p>
                                                            </td>
                                                            <td class="text-center">     
                                                                <p class="text-md" style="font-size:16px;"></p>
                                                            </td>
                                                            <td class="text-end">
                                                                <p class="text-md" style="font-size:16px;"> $</p>
                                                            </td>
                                                            <td class="text-end">        
                                                                <p class="text-md" style="font-size:16px; text-align: right !important;"> $</p>    
                                                            </td>
                                                            <td class="d-flex justify-content-center">
                                                                <button type="button" class="btn btn-sm" style="font-size:12px; color:white; background-color: red">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>  
                                                            </td>
                                                        </tr>   
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <a href="{{ route('order-services.index') }}" class="btn btn-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-primary" id="btnSave">Registrar</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #select_prod {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            height: 38px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
    </style>

    <!-- DataTable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

    <!-- DataTable JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.js-example-basic-single').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            
            // ============================================
            // 1. OBTENER VEHÍCULOS AL SELECCIONAR CLIENTE
            // ============================================
            $('#user_id').on('change', function() {
                var userId = $(this).val();
                
                if (userId) {
                    // Mostrar loading
                    $('#vehicle_id').html('<option value="">Cargando vehículos...</option>');
                    
                    $.ajax({
                        url: '/admin/get-vehicles-by-user/' + userId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Limpiar el select de vehículos
                            $('#vehicle_id').empty();
                            $('#vehicle_id').append('<option value="">Listado de bicicletas</option>');
                            
                            if (data.length === 0) {
                                $('#vehicle_id').append('<option value="" disabled>No hay vehículos registrados</option>');
                            } else {
                                // Agregar los vehículos al select
                                $.each(data, function(key, vehicle) {
                                    var displayName = vehicle.model || 'Vehículo sin modelo';
                                    
                                    var option = $('<option>', {
                                        value: vehicle.id,
                                        text: displayName,
                                        'data-type': vehicle.type || '',
                                        'data-brand': vehicle.brand_name || '',
                                        'data-brand-id': vehicle.brand_id || '',
                                        'data-model': vehicle.model || '',
                                        'data-created': vehicle.created_at || ''
                                    });
                                    
                                    $('#vehicle_id').append(option);
                                });
                            }
                            
                            // Actualizar Select2
                            $('#vehicle_id').trigger('change');
                            
                            // Limpiar la información del vehículo
                            clearVehicleInfo();
                            
                            // Obtener información del cliente
                            getUserInfo(userId);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al cargar los vehículos:', error);
                            $('#vehicle_id').empty();
                            $('#vehicle_id').append('<option value="">Error al cargar vehículos</option>');
                            $('#vehicle_id').trigger('change');
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudieron cargar los vehículos del cliente seleccionado.'
                            });
                        }
                    });
                } else {
                    // Si no se selecciona ningún cliente, limpiar el select
                    $('#vehicle_id').empty();
                    $('#vehicle_id').append('<option value="">Listado de bicicletas</option>');
                    $('#vehicle_id').trigger('change');
                    clearVehicleInfo();
                    clearUserInfo();
                }
            });
            
            // ============================================
            // 2. MOSTRAR INFORMACIÓN DEL VEHÍCULO SELECCIONADO
            // ============================================
            $('#vehicle_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                
                if (selectedOption.val()) {
                    // Obtener los datos del vehículo seleccionado
                    var type = selectedOption.data('type');
                    var brand = selectedOption.data('brand');
                    var model = selectedOption.data('model');
                    var created = selectedOption.data('created');
                    
                    // Mostrar la información
                    $('#vehicle-type').text(type || 'No especificado');
                    $('#vehicle-brand').text(brand || 'No especificado');
                    $('#vehicle-model').text(model || 'No especificado');
                    $('#vehicle-created').text(created || 'No registrado');
                } else {
                    clearVehicleInfo();
                }
            });
            
            // ============================================
            // 3. OBTENER INFORMACIÓN DEL CLIENTE
            // ============================================
            function getUserInfo(userId) {
                $.ajax({
                    url: '/admin/get-user-info/' + userId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#user-name').text(data.name || 'No registrado');
                        $('#user-document').text(data.document || 'No registrado');
                        $('#user-phone').text(data.phone || 'No registrado');
                        $('#user-address').text(data.address || 'No registrada');
                        $('#user-city').text(data.city || 'No especificada');
                        $('#user-email').text(data.email || 'No registrado');
                        
                        // Opcional: Mostrar un badge con el estado
                        if (data.status == 1) {
                            $('#user-status').html('<span class="badge bg-success">Activo</span>');
                        } else {
                            $('#user-status').html('<span class="badge bg-danger">Inactivo</span>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener información del usuario:', error);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'No se pudo cargar la información completa del cliente.'
                        });
                    }
                });
            }

            // ============================================
            // 4. LIMPIAR INFORMACIÓN
            // ============================================
            function clearUserInfo() {
                $('#user-name').text('-');
                $('#user-document').text('-');
                $('#user-phone').text('-');
                $('#user-address').text('-');
                $('#user-city').text('-');
                $('#user-email').text('-');
            }
            
            // ============================================
            // 4. LIMPIAR INFORMACIÓN
            // ============================================
            function clearVehicleInfo() {
                $('#vehicle-type').text('-');
                $('#vehicle-brand').text('-');
                $('#vehicle-model').text('-');
                $('#vehicle-created').text('-');
            }
            
            function clearUserInfo() {
                $('#user-phone').text('-');
                $('#user-address').text('-');
                $('#user-email').text('-');
            }
            
            // ============================================
            // 5. VALIDACIÓN DEL FORMULARIO
            // ============================================
            $('form').on('submit', function(e) {
                var userId = $('#user_id').val();
                var vehicleId = $('#vehicle_id').val();
                
                if (!userId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo requerido',
                        text: 'Por favor, seleccione un cliente.'
                    });
                    $('#user_id').focus();
                    return false;
                }
                
                if (!vehicleId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo requerido',
                        text: 'Por favor, seleccione una bicicleta.'
                    });
                    $('#vehicle_id').focus();
                    return false;
                }
            });
            
            // ============================================
            // 6. PREVISUALIZACIÓN DE IMÁGENES (Opcional)
            // ============================================
            $('#imagenes').on('change', function() {
                var files = $(this)[0].files;
                var validExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
                var maxSize = 5 * 1024 * 1024; // 5MB
                
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    
                    // Validar extensión
                    if (!validExtensions.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Formato no válido',
                            text: 'El archivo ' + file.name + ' no tiene un formato permitido. Solo se permiten: JPEG, PNG, JPG.'
                        });
                        $(this).val('');
                        return false;
                    }
                    
                    // Validar tamaño
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo muy grande',
                            text: 'El archivo ' + file.name + ' excede el tamaño máximo permitido de 5MB.'
                        });
                        $(this).val('');
                        return false;
                    }
                }
            });
            
            // ============================================
            // 7. MOSTRAR MENSAJES DE ERROR (si existen)
            // ============================================
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            @endif
            
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: '{{ session('success') }}'
                });
            @endif
        });
    </script>

    {{-- script products for table --}}
    <script>
        $(document).ready(function() {
            // Array para almacenar los items temporalmente
            let items = [];
            let itemIdCounter = 0;
    
            // ============================================
            // 1. CARGAR PRECIO DEL PRODUCTO SELECCIONADO
            // ============================================
            $('#select_prod').on('change', function() {
                var productId = $(this).val();
                
                if (productId) {
                    $.ajax({
                        url: '/admin/get-product-price/' + productId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#unit_price').val(data.price || 0);
                            calculateSubtotal();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al cargar el precio:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo cargar el precio del producto.'
                            });
                        }
                    });
                } else {
                    $('#unit_price').val('');
                    calculateSubtotal();
                }
            });
    
            // ============================================
            // 2. CALCULAR SUBTOTAL EN TIEMPO REAL
            // ============================================
            $('#quantity, #unit_price, #discount').on('keyup change', function() {
                calculateSubtotal();
            });
    
            function calculateSubtotal() {
                var quantity = parseFloat($('#quantity').val()) || 0;
                var unitPrice = parseFloat($('#unit_price').val()) || 0;
                var discount = parseFloat($('#discount').val()) || 0;
                
                var subtotal = (quantity * unitPrice) - discount;
                
                // Asegurar que el subtotal no sea negativo
                subtotal = subtotal < 0 ? 0 : subtotal;
                
                // Mostrar el subtotal en el campo correspondiente
                $('#subtotal_display').val(subtotal.toFixed(2));
            }
    
            // ============================================
            // 3. AGREGAR ITEM A LA TABLA
            // ============================================
            $('#btnAddItem').on('click', function() {
                var productId = $('#select_prod').val();
                var productName = $('#select_prod option:selected').text();
                var quantity = parseInt($('#quantity').val()) || 0;
                var unitPrice = parseFloat($('#unit_price').val()) || 0;
                var discount = parseFloat($('#discount').val()) || 0;
                var subtotal = parseFloat($('#subtotal_display').val()) || 0;
    
                // Validaciones
                if (!productId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo requerido',
                        text: 'Por favor, seleccione un producto o servicio.'
                    });
                    $('#select_prod').focus();
                    return false;
                }
    
                if (quantity <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cantidad inválida',
                        text: 'La cantidad debe ser mayor a 0.'
                    });
                    $('#quantity').focus();
                    return false;
                }
    
                if (unitPrice <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Precio inválido',
                        text: 'El precio debe ser mayor a 0.'
                    });
                    $('#unit_price').focus();
                    return false;
                }
    
                // Verificar si el producto ya fue agregado
                var existingItem = items.find(item => item.productId === parseInt(productId));
                if (existingItem) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto duplicado',
                        text: 'Este producto ya ha sido agregado. ¿Desea actualizar la cantidad?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, actualizar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Actualizar el item existente
                            existingItem.quantity = quantity;
                            existingItem.unitPrice = unitPrice;
                            existingItem.discount = discount;
                            existingItem.subtotal = subtotal;
                            renderTable();
                            
                            // Limpiar campos
                            clearItemFields();
                        }
                    });
                    return;
                }
    
                // Crear nuevo item
                var newItem = {
                    id: ++itemIdCounter,
                    productId: parseInt(productId),
                    productName: productName,
                    quantity: quantity,
                    unitPrice: unitPrice,
                    discount: discount,
                    subtotal: subtotal
                };
    
                items.push(newItem);
                renderTable();
    
                // Limpiar campos
                clearItemFields();
    
                Swal.fire({
                    icon: 'success',
                    title: 'Agregado',
                    text: 'Producto agregado correctamente.',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
    
            // ============================================
            // 4. RENDERIZAR TABLA ( TOTAL GENERAL )
            // ============================================
            function renderTable() {
                var tbody = $('#orderservicesTable tbody');
                tbody.empty();
    
                if (items.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="5" class="text-muted py-3">
                                No hay productos agregados
                            </td>
                        </tr>
                    `);
                    return;
                }
    
                var totalGeneral = 0;
    
                $.each(items, function(index, item) {
                    totalGeneral += item.subtotal;
                    
                    var row = `
                        <tr data-item-id="${item.id}">
                            <td class="text-start">
                                <p class="text-md" style="font-size:16px;">${item.productName}</p>
                                <input type="hidden" name="items[${index}][product_id]" value="${item.productId}">
                                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                                <input type="hidden" name="items[${index}][unit_price]" value="${item.unitPrice}">
                                <input type="hidden" name="items[${index}][discount]" value="${item.discount}">
                                <input type="hidden" name="items[${index}][subtotal]" value="${item.subtotal}">
                            </td>
                            <td class="text-center">     
                                <p class="text-md" style="font-size:16px;">${item.quantity}</p>
                            </td>
                            <td class="text-end">
                                <p class="text-md" style="font-size:16px;">$${item.unitPrice.toFixed(2)}</p>
                            </td>
                            <td class="text-end">        
                                <p class="text-md" style="font-size:16px;">$${item.subtotal.toFixed(2)}</p>    
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-delete-item text-center" style="background-color: red; font-size:14px; color: #fff;" data-item-id="${item.id}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    
                    tbody.append(row);
                });
    
                // Agregar fila de total
                tbody.append(`
                    <tr class="table-success" style="font-size: 1.1rem;">
                        <td colspan="3" class="text-end fw-bold">
                            TOTAL GENERAL:
                        </td>
                        <td class="text-end fw-bold">
                            $${totalGeneral.toFixed(2)}
                        </td>
                        <td></td>
                    </tr>
                `);
    
                // Agregar campo oculto con el total
                $('#total_general').remove();
                tbody.append(`
                    <input type="hidden" id="total_general" name="total_general" value="${totalGeneral.toFixed(2)}">
                `);
    
                // Evento para eliminar items
                $('.btn-delete-item').on('click', function() {
                    var itemId = $(this).data('item-id');
                    deleteItem(itemId);
                });
            }
    
            // ============================================
            // 5. ELIMINAR ITEM
            // ============================================
            function deleteItem(itemId) {
                Swal.fire({
                    title: '¿Eliminar producto?',
                    text: '¿Estás seguro de que deseas eliminar este producto de la lista?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        items = items.filter(item => item.id !== itemId);
                        renderTable();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'Producto eliminado correctamente.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
    
            // ============================================
            // 6. LIMPIAR CAMPOS DEL FORMULARIO
            // ============================================
            function clearItemFields() {
                $('#select_prod').val('').trigger('change');
                $('#quantity').val(1);
                $('#unit_price').val('');
                $('#discount').val(0);
                $('#subtotal_display').val('0.00');
            }
    
            // ============================================
            // 7. VALIDAR ANTES DE ENVIAR EL FORMULARIO
            // ============================================
            $('form').on('submit', function(e) {
                var userId = $('#user_id').val();
                var vehicleId = $('#vehicle_id').val();
                
                if (!userId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo requerido',
                        text: 'Por favor, seleccione un cliente.'
                    });
                    $('#user_id').focus();
                    return false;
                }
                
                if (!vehicleId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo requerido',
                        text: 'Por favor, seleccione una bicicleta.'
                    });
                    $('#vehicle_id').focus();
                    return false;
                }
    
                if (items.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lista vacía',
                        text: 'Por favor, agregue al menos un producto o servicio.'
                    });
                    return false;
                }
    
                // Mostrar confirmación antes de enviar
                e.preventDefault();
                Swal.fire({
                    title: '¿Confirmar orden?',
                    text: '¿Estás seguro de que deseas registrar esta orden de servicio?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar el formulario
                        $('form')[0].submit();
                    }
                });
            });
    
            // ============================================
            // 8. INICIALIZAR SUBTOTAL
            // ============================================
            $('#subtotal_display').val('0.00');
        });
    </script>


@endpush