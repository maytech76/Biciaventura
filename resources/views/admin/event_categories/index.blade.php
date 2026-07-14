@extends('admin.layouts.master')

@section('title', 'Categorias Sport')

@section('content')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        
                        {{-- Start Table--}} 
                        <div class="card card-table">
                            <div class="card-body">
                                
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-2">
                                    <h3 class="fw-bold">Listado de categorias por evento</h3>
                                    <form class="d-inline-flex">
                                        <a href="javascript:void(0)" 
                                            class="align-items-center btn btn-theme d-flex" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#createCategoryModal">
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

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="table-responsive category-table">
                                    <div>
                                        <table class="table theme-table" id="table_id">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th class="text-center">Edad Min</th>
                                                    <th class="text-center">Edad Max</th>
                                                    <th>Género</th>
                                                    <th>Evento</th>
                                                    <th>Status</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse ($event_categories as $category)
                                                <tr>
                                                    <td class="text-start">{{ $category->name }}</td>
                                                    <td class="text-success text-center">{{ $category->min_age ?? 'N/A' }}</td>
                                                    <td class="text-warning text-center">{{ $category->max_age ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($category->gender_restriction == 'MASCULINO')
                                                            <span class="text-primary"><i class="ri-men-line me-1"></i>MASCULINO</span>
                                                        @elseif ($category->gender_restriction == 'FEMENINO')
                                                            <span class="text-danger"><i class="ri-women-line me-1"></i>FEMENINO</span>
                                                        @else
                                                            <span class="text-secondary">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $category->event->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($category->status == 1)
                                                            <span class="p-1 px-2" style="background:#099553 ; color: white; border-bottom: none; border-radius:5px;">Activa</span>
                                                        @else
                                                            <span class="py-1 px-2" style="background:#ba1717 ; color: white; border-bottom: none; border-radius:5px;">Inactiva</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                    class="text-warning edit-category" 
                                                                    data-id="{{ $category->id }}"
                                                                    data-name="{{ $category->name }}"
                                                                    data-event_id="{{ $category->event_id }}"
                                                                    data-min_age="{{ $category->min_age }}"
                                                                    data-max_age="{{ $category->max_age }}"
                                                                    data-gender="{{ $category->gender_restriction }}"
                                                                    data-status="{{ $category->status }}">
                                                                        <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   

                                                <!-- Modal de Eliminación -->
                                                <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background: linear-gradient(135deg, #ec99a2 0%, #a72b38 100%); color: white; border-bottom: none;">

                                                                <h5 class="modal-title" style="color: white;" id="deleteModalLabel">Confirmar Eliminación</h5>
                                                                
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que quieres eliminar la categoría <strong>"{{ $category->name }}"</strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn" style="background-color: #6c757d; color: white; border: none; border-radius: 0.375rem;" data-bs-dismiss="modal">
                                                                    Cancelar
                                                                </button>
                                                                <form action="{{ route('event_categories.destroy', $category->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn" style="background-color: #bc1a1a; color: white; border: none; border-radius: 0.375rem;">
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
                                                            <p class="mt-2">No hay categorías registradas</p>
                                                            <a href="javascript:void(0)" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                                                <i class="ri-add-line"></i> Crear primera categoría
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        {{-- Paginacion --}}
                                        <div class="d-flex justify-content-center align-items-center mt-3">
                                            {{ $event_categories->links() }}
                                        </div>
                                    </div>
                                </div>           
                            </div>
                        </div> {{-- end table --}}

                        <!-- Modal para Crear Nueva Categoría -->
                        <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="createCategoryModalLabel">
                                            Registro nueva categoría
                                        </h5>
                                       
                                    </div>
                                    
                                    <form action="{{ route('event_categories.store') }}" method="POST" id="createCategoryForm">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                {{-- Nombre de la Categoría --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           class="form-control border" 
                                                           id="category_name" 
                                                           name="name" 
                                                           placeholder="Ej: ELITE, JUNIOR, MASTER" 
                                                           required>
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                </div>

                                                {{-- Evento (Select) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Evento <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="event_id" name="event_id" required>
                                                        <option value="">Seleccione un Evento</option>
                                                        @foreach($events as $event)
                                                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="event-error"></div>
                                                </div>

                                                {{-- Edad Mínima --}}
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">Edad Mínima</label>
                                                    <input type="number" 
                                                           class="form-control border" 
                                                           id="min_age" 
                                                           name="min_age" 
                                                           placeholder="Ej: 18" 
                                                           min="0" 
                                                           max="99">
                                                    <div class="invalid-feedback" id="min_age-error"></div>
                                                </div>

                                                {{-- Edad Máxima --}}
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">Edad Máxima</label>
                                                    <input type="number" 
                                                           class="form-control border" 
                                                           id="max_age" 
                                                           name="max_age" 
                                                           placeholder="Ej: 30" 
                                                           min="0" 
                                                           max="99">
                                                    <div class="invalid-feedback" id="max_age-error"></div>
                                                </div>

                                                {{-- Género --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Género</label>
                                                    <select class="form-select border" id="gender_restriction" name="gender_restriction">
                                                        <option value="">Seleccione un Género</option>
                                                        <option value="MASCULINO">Masculino</option>
                                                        <option value="FEMENINO">Femenino</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="gender-error"></div>
                                                </div>

                                                {{-- Status --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Status</label>
                                                    <select class="form-select border" id="status" name="status">
                                                        <option value="1">Activa</option>
                                                        <option value="0">Inactiva</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-theme" id="btnSubmit">
                                                Registrar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> {{-- Final Modal nueva Categoria --}}


                        <!-- Modal para Editar Categoría -->
                        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #e8ec99 0%, #a7a52b 100%); color: rgb(22, 22, 22); border-bottom: none;">

                                        <h5 class="modal-title" id="editCategoryModalLabel">
                                            Editar Categoría
                                        </h5>
                                       
                                    </div>
                                    
                                    <form action="" method="POST" id="editCategoryForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                {{-- ID oculto --}}
                                                <input type="hidden" id="edit_category_id" name="category_id">
                                                
                                                {{-- Nombre de la Categoría --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_category_name" 
                                                        name="name" 
                                                        placeholder="Ej: ELITE, JUNIOR, MASTER" 
                                                        required>
                                                    <div class="invalid-feedback" id="edit-name-error"></div>
                                                </div>

                                                {{-- Evento (Select) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Evento <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="edit_event_id" name="event_id" required>
                                                        <option value="">Seleccione un Evento</option>
                                                        @foreach($events as $event)
                                                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-event-error"></div>
                                                </div>

                                                {{-- Edad Mínima --}}
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">Edad Mínima</label>
                                                    <input type="number" 
                                                        class="form-control border" 
                                                        id="edit_min_age" 
                                                        name="min_age" 
                                                        placeholder="Ej: 18" 
                                                        min="0" 
                                                        max="99">
                                                    <div class="invalid-feedback" id="edit-min_age-error"></div>
                                                </div>

                                                {{-- Edad Máxima --}}
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">Edad Máxima</label>
                                                    <input type="number" 
                                                        class="form-control border" 
                                                        id="edit_max_age" 
                                                        name="max_age" 
                                                        placeholder="Ej: 30" 
                                                        min="0" 
                                                        max="99">
                                                    <div class="invalid-feedback" id="edit-max_age-error"></div>
                                                </div>

                                                {{-- Género --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Género</label>
                                                    <select class="form-select border" id="edit_gender_restriction" name="gender_restriction">
                                                        <option value="">Seleccione un Género</option>
                                                        <option value="MASCULINO">Masculino</option>
                                                        <option value="FEMENINO">Femenino</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-gender-error"></div>
                                                </div>

                                                {{-- Status --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Status</label>
                                                    <select class="form-select border" id="edit_status" name="status">
                                                        <option value="1">Activa</option>
                                                        <option value="0">Inactiva</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                               Cancelar
                                            </button>
                                            <button type="submit" class="btn" style="background: #dad709; color: #000;" id="editBtnSubmit">
                                               Actualizar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>{{-- Final Modal editar Categoria --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
    <style>
        .modal-header .btn-close {
            background-color: transparent;
        }
        
        .modal-body .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: #2ebe93;
            box-shadow: 0 0 0 0.2rem rgba(46, 190, 147, 0.25);
        }
        
        .modal-body .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .invalid-feedback {
            display: none;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        
        .invalid-feedback.show {
            display: block;
        }

        .invalid-feedback {
            display: none;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            color: #dc3545;
        }
        
        .invalid-feedback.show {
            display: block;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }
        
        .is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: #2ebe93;
            box-shadow: 0 0 0 0.2rem rgba(46, 190, 147, 0.25);
        }
    </style>
@endpush

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- UN SOLO SCRIPT PARA REGISTRO DE CATEGORÍAS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // ============================================
            // 1. OBTENER ELEMENTOS DEL DOM
            // ============================================
            const form = document.getElementById('createCategoryForm');
            const modal = document.getElementById('createCategoryModal');
            const submitBtn = document.getElementById('btnSubmit');
            const nameInput = document.getElementById('category_name');
            const eventSelect = document.getElementById('event_id');
            const minAgeInput = document.getElementById('min_age');
            const maxAgeInput = document.getElementById('max_age');
            const statusSelect = document.getElementById('status');
            
            // ============================================
            // 2. VARIABLES DE CONTROL
            // ============================================
            let isSubmitting = false;
            let isSuccess = false;
            
            // Verificar que los elementos existan
            if (!form || !modal) {
                console.error('❌ Elementos del formulario no encontrados');
                return;
            }
            
            // ============================================
            // 3. FUNCIÓN PARA LIMPIAR EL FORMULARIO
            // ============================================
            function resetForm() {
                form.reset();
                
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                    el.style.display = 'none';
                });
                
                if (statusSelect) statusSelect.value = '1';
                isSubmitting = false;
            }
            
            // ============================================
            // 4. LIMPIAR AL CERRAR EL MODAL Y MOSTRAR SWEETALERT
            // ============================================
            modal.addEventListener('hidden.bs.modal', function() {
                // Si fue exitoso, mostrar SweetAlert
                if (isSuccess) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Categoria creada con exito..!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Resetear la bandera después de mostrar el mensaje
                    isSuccess = false;
                }
                
                // Limpiar el formulario
                resetForm();
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ri-save-line me-1"></i>Guardar Categoría';
            });
            
            // ============================================
            // 5. FUNCIONES DE VALIDACIÓN
            // ============================================
            function validateField(input, fieldName) {
                const errorElement = document.getElementById(input.id + '-error');
                const value = input.value.trim();
                
                if (input.tagName === 'SELECT') {
                    if (input.hasAttribute('required') && (!value || value === '')) {
                        input.classList.add('is-invalid');
                        if (errorElement) {
                            errorElement.textContent = `El campo "${fieldName}" es obligatorio`;
                            errorElement.style.display = 'block';
                        }
                        return false;
                    }
                    input.classList.remove('is-invalid');
                    if (errorElement) {
                        errorElement.textContent = '';
                        errorElement.style.display = 'none';
                    }
                    return true;
                }
                
                if (input.hasAttribute('required') && value === '') {
                    input.classList.add('is-invalid');
                    if (errorElement) {
                        errorElement.textContent = `El campo "${fieldName}" es obligatorio`;
                        errorElement.style.display = 'block';
                    }
                    return false;
                }
                
                if (input.type === 'number' && value !== '') {
                    const numValue = parseInt(value);
                    if (isNaN(numValue) || numValue < 0) {
                        input.classList.add('is-invalid');
                        if (errorElement) {
                            errorElement.textContent = `El campo "${fieldName}" debe ser un número válido`;
                            errorElement.style.display = 'block';
                        }
                        return false;
                    }
                }
                
                input.classList.remove('is-invalid');
                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
                return true;
            }
            
            function validateAges() {
                const minError = document.getElementById('min_age-error');
                const maxError = document.getElementById('max_age-error');
                let isValid = true;
                
                if (minError) {
                    minError.textContent = '';
                    minError.style.display = 'none';
                }
                if (maxError) {
                    maxError.textContent = '';
                    maxError.style.display = 'none';
                }
                
                if (minAgeInput) minAgeInput.classList.remove('is-invalid');
                if (maxAgeInput) maxAgeInput.classList.remove('is-invalid');
                
                if (minAgeInput.value && maxAgeInput.value) {
                    const min = parseInt(minAgeInput.value);
                    const max = parseInt(maxAgeInput.value);
                    
                    if (min > max) {
                        minAgeInput.classList.add('is-invalid');
                        maxAgeInput.classList.add('is-invalid');
                        if (minError) {
                            minError.textContent = 'La edad mínima no puede ser mayor que la edad máxima';
                            minError.style.display = 'block';
                        }
                        if (maxError) {
                            maxError.textContent = 'La edad máxima no puede ser menor que la edad mínima';
                            maxError.style.display = 'block';
                        }
                        isValid = false;
                    }
                }
                
                return isValid;
            }
            
            // ============================================
            // 6. CONFIGURAR CAMPOS PARA VALIDACIÓN
            // ============================================
            const fields = [
                { id: 'category_name', name: 'Nombre de la categoría', required: true },
                { id: 'event_id', name: 'Evento', required: true },
                { id: 'min_age', name: 'Edad mínima', required: false },
                { id: 'max_age', name: 'Edad máxima', required: false },
                { id: 'gender_restriction', name: 'Género', required: false }
            ];
            
            fields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input) {
                    input.addEventListener('blur', function() {
                        validateField(this, field.name);
                    });
                    input.addEventListener('input', function() {
                        if (this.classList.contains('is-invalid')) {
                            validateField(this, field.name);
                        }
                    });
                    if (field.required) {
                        input.setAttribute('required', 'required');
                    }
                }
            });
            
            // ============================================
            // 7. EVENTOS PARA VALIDAR EDADES
            // ============================================
            if (minAgeInput) {
                minAgeInput.addEventListener('change', validateAges);
                minAgeInput.addEventListener('input', validateAges);
            }
            if (maxAgeInput) {
                maxAgeInput.addEventListener('change', validateAges);
                maxAgeInput.addEventListener('input', validateAges);
            }
            
            // ============================================
            // 8. CONVERTIR A MAYÚSCULAS EN TIEMPO REAL
            // ============================================
            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
            
            // ============================================
            // 9. ENVIAR FORMULARIO - UN SOLO EVENTO
            // ============================================
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (isSubmitting) {
                    console.log('⏳ Formulario ya está siendo enviado...');
                    return;
                }
                
                // Validar todos los campos
                let isValid = true;
                fields.forEach(field => {
                    const input = document.getElementById(field.id);
                    if (input && !validateField(input, field.name)) {
                        isValid = false;
                    }
                });
                
                if (!validateAges()) {
                    isValid = false;
                }
                
                if (!isValid) {
                    Swal.fire({
                        title: '¡Campos incompletos!',
                        text: 'Por favor, completa todos los campos obligatorios correctamente.',
                        icon: 'warning',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                
                // ============================================
                // 10. ENVÍO AJAX
                // ============================================
                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Guardando...';
                
                const formData = new FormData(this);
                
                console.log('📤 Enviando datos:', Object.fromEntries(formData));
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📥 Respuesta del servidor:', data);
                    
                    if (data.success) {
                        isSuccess = true;
                        
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                        
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                        
                    } else {
                        if (data.errors) {
                            let errorMessages = [];
                            Object.keys(data.errors).forEach(key => {
                                const input = document.getElementById(key);
                                const errorElement = document.getElementById(key + '-error');
                                if (input && errorElement) {
                                    input.classList.add('is-invalid');
                                    errorElement.textContent = data.errors[key][0];
                                    errorElement.style.display = 'block';
                                    errorMessages.push(data.errors[key][0]);
                                }
                            });
                            
                            if (errorMessages.length > 0) {
                                Swal.fire({
                                    title: '¡Error de validación!',
                                    text: errorMessages[0],
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        } else {
                            Swal.fire({
                                title: '¡Error!',
                                text: data.message || 'Error al crear la categoría',
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                        
                        isSubmitting = false;
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Registar';
                    }
                })
                .catch(error => {
                    console.error('❌ Error en la petición:', error);
                    
                    Swal.fire({
                        title: '¡Error!',
                        text: 'Error al procesar la solicitud. Por favor, intenta nuevamente.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar'
                    });
                    
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ri-save-line me-1"></i>Guardar Categoría';
                });
            });
            
            console.log('✅ Script de categorías inicializado correctamente');
            
        });
    </script>

    {{-- Script para actualizar registro --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // ============================================
            // 1. OBTENER ELEMENTOS DEL DOM
            // ============================================
            const editModal = document.getElementById('editCategoryModal');
            const editForm = document.getElementById('editCategoryForm');
            const editSubmitBtn = document.getElementById('editBtnSubmit');
            
            // Campos de edición
            const editId = document.getElementById('edit_category_id');
            const editName = document.getElementById('edit_category_name');
            const editEventId = document.getElementById('edit_event_id');
            const editMinAge = document.getElementById('edit_min_age');
            const editMaxAge = document.getElementById('edit_max_age');
            const editGender = document.getElementById('edit_gender_restriction');
            const editStatus = document.getElementById('edit_status');
            
            // ============================================
            // 2. VARIABLES DE CONTROL
            // ============================================
            let isEditSubmitting = false;
            let isEditSuccess = false;
            
            // ============================================
            // 3. CARGAR DATOS EN EL MODAL DE EDICIÓN
            // ============================================
            document.querySelectorAll('.edit-category').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Obtener datos del data-* attributes
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const eventId = this.getAttribute('data-event_id');
                    const minAge = this.getAttribute('data-min_age');
                    const maxAge = this.getAttribute('data-max_age');
                    const gender = this.getAttribute('data-gender');
                    const status = this.getAttribute('data-status');
                    
                    console.log('Editando categoría ID:', id);
                    
                    // Limpiar errores anteriores
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                    document.querySelectorAll('.invalid-feedback').forEach(el => {
                        el.textContent = '';
                        el.style.display = 'none';
                    });
                    
                    // Llenar el formulario con los datos
                    if (editId) editId.value = id;
                    if (editName) editName.value = name || '';
                    if (editEventId) editEventId.value = eventId || '';
                    if (editMinAge) editMinAge.value = minAge || '';
                    if (editMaxAge) editMaxAge.value = maxAge || '';
                    if (editGender) editGender.value = gender || '';
                    if (editStatus) editStatus.value = status || '1';
                    
                    // Configurar la acción del formulario con el ID correcto
                    const url = `{{ route('event_categories.update', '') }}/${id}`;
                    editForm.action = url;
                    
                    // Mostrar el modal
                    const modal = new bootstrap.Modal(editModal);
                    modal.show();
                });
            });
            
            // ============================================
            // 4. LIMPIAR AL CERRAR EL MODAL
            // ============================================
            editModal.addEventListener('hidden.bs.modal', function() {
                // Si fue exitoso, mostrar SweetAlert
                if (isEditSuccess) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Categoria actualizada con exito..!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    isEditSuccess = false;
                }
                
                // Resetear formulario
                editForm.reset();
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                    el.style.display = 'none';
                });
                
                isEditSubmitting = false;
                editSubmitBtn.disabled = false;
                editSubmitBtn.innerHTML = '<i class="ri-save-line me-1"></i>Actualizar Categoría';
            });
            
            // ============================================
            // 5. VALIDACIÓN DE EDICIÓN
            // ============================================
            function validateEditField(input, fieldName) {
                const errorElement = document.getElementById('edit-' + input.id.replace('edit_', '') + '-error');
                const value = input.value.trim();
                
                if (input.tagName === 'SELECT') {
                    if (input.hasAttribute('required') && (!value || value === '')) {
                        input.classList.add('is-invalid');
                        if (errorElement) {
                            errorElement.textContent = `El campo "${fieldName}" es obligatorio`;
                            errorElement.style.display = 'block';
                        }
                        return false;
                    }
                    input.classList.remove('is-invalid');
                    if (errorElement) {
                        errorElement.textContent = '';
                        errorElement.style.display = 'none';
                    }
                    return true;
                }
                
                if (input.hasAttribute('required') && value === '') {
                    input.classList.add('is-invalid');
                    if (errorElement) {
                        errorElement.textContent = `El campo "${fieldName}" es obligatorio`;
                        errorElement.style.display = 'block';
                    }
                    return false;
                }
                
                if (input.type === 'number' && value !== '') {
                    const numValue = parseInt(value);
                    if (isNaN(numValue) || numValue < 0) {
                        input.classList.add('is-invalid');
                        if (errorElement) {
                            errorElement.textContent = `El campo "${fieldName}" debe ser un número válido`;
                            errorElement.style.display = 'block';
                        }
                        return false;
                    }
                }
                
                input.classList.remove('is-invalid');
                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
                return true;
            }
            
            function validateEditAges() {
                const minError = document.getElementById('edit-min_age-error');
                const maxError = document.getElementById('edit-max_age-error');
                let isValid = true;
                
                if (minError) {
                    minError.textContent = '';
                    minError.style.display = 'none';
                }
                if (maxError) {
                    maxError.textContent = '';
                    maxError.style.display = 'none';
                }
                
                if (editMinAge) editMinAge.classList.remove('is-invalid');
                if (editMaxAge) editMaxAge.classList.remove('is-invalid');
                
                if (editMinAge.value && editMaxAge.value) {
                    const min = parseInt(editMinAge.value);
                    const max = parseInt(editMaxAge.value);
                    
                    if (min > max) {
                        editMinAge.classList.add('is-invalid');
                        editMaxAge.classList.add('is-invalid');
                        if (minError) {
                            minError.textContent = 'La edad mínima no puede ser mayor que la edad máxima';
                            minError.style.display = 'block';
                        }
                        if (maxError) {
                            maxError.textContent = 'La edad máxima no puede ser menor que la edad mínima';
                            maxError.style.display = 'block';
                        }
                        isValid = false;
                    }
                }
                
                return isValid;
            }
            
            // ============================================
            // 6. CONFIGURAR VALIDACIÓN DE EDICIÓN
            // ============================================
            const editFields = [
                { id: 'edit_category_name', name: 'Nombre de la categoría', required: true },
                { id: 'edit_event_id', name: 'Evento', required: true },
                { id: 'edit_min_age', name: 'Edad mínima', required: false },
                { id: 'edit_max_age', name: 'Edad máxima', required: false },
                { id: 'edit_gender_restriction', name: 'Género', required: false }
            ];
            
            editFields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input) {
                    input.addEventListener('blur', function() {
                        validateEditField(this, field.name);
                    });
                    input.addEventListener('input', function() {
                        if (this.classList.contains('is-invalid')) {
                            validateEditField(this, field.name);
                        }
                    });
                    if (field.required) {
                        input.setAttribute('required', 'required');
                    }
                }
            });
            
            if (editMinAge) {
                editMinAge.addEventListener('change', validateEditAges);
                editMinAge.addEventListener('input', validateEditAges);
            }
            if (editMaxAge) {
                editMaxAge.addEventListener('change', validateEditAges);
                editMaxAge.addEventListener('input', validateEditAges);
            }
            
            // ============================================
            // 7. CONVERTIR A MAYÚSCULAS EN TIEMPO REAL
            // ============================================
            if (editName) {
                editName.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
            
            // ============================================
            // 8. ENVIAR FORMULARIO DE EDICIÓN
            // ============================================
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (isEditSubmitting) {
                    console.log('⏳ Formulario de edición ya está siendo enviado...');
                    return;
                }
                
                // Validar todos los campos
                let isValid = true;
                editFields.forEach(field => {
                    const input = document.getElementById(field.id);
                    if (input && !validateEditField(input, field.name)) {
                        isValid = false;
                    }
                });
                
                if (!validateEditAges()) {
                    isValid = false;
                }
                
                if (!isValid) {
                    Swal.fire({
                        title: '¡Campos incompletos!',
                        text: 'Por favor, completa todos los campos obligatorios correctamente.',
                        icon: 'warning',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                
                // ============================================
                // 9. ENVÍO AJAX DE EDICIÓN
                // ============================================
                isEditSubmitting = true;
                editSubmitBtn.disabled = true;
                editSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Actualizando...';
                
                const formData = new FormData(this);
                
                console.log('📤 Enviando datos de edición:', Object.fromEntries(formData));
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📥 Respuesta del servidor:', data);
                    
                    if (data.success) {
                        isEditSuccess = true;
                        
                        const modalInstance = bootstrap.Modal.getInstance(editModal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                        
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                        
                    } else {
                        if (data.errors) {
                            let errorMessages = [];
                            Object.keys(data.errors).forEach(key => {
                                const input = document.getElementById('edit_' + key);
                                const errorElement = document.getElementById('edit-' + key + '-error');
                                if (input && errorElement) {
                                    input.classList.add('is-invalid');
                                    errorElement.textContent = data.errors[key][0];
                                    errorElement.style.display = 'block';
                                    errorMessages.push(data.errors[key][0]);
                                }
                            });
                            
                            if (errorMessages.length > 0) {
                                Swal.fire({
                                    title: '¡Error de validación!',
                                    text: errorMessages[0],
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        } else {
                            Swal.fire({
                                title: '¡Error!',
                                text: data.message || 'Error al actualizar la categoría',
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                        
                        isEditSubmitting = false;
                        editSubmitBtn.disabled = false;
                        editSubmitBtn.innerHTML = '<i class="ri-save-line me-1"></i>Actualizar Categoría';
                    }
                })
                .catch(error => {
                    console.error('❌ Error en la petición:', error);
                    
                    Swal.fire({
                        title: '¡Error!',
                        text: 'Error al procesar la solicitud. Por favor, intenta nuevamente.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar'
                    });
                    
                    isEditSubmitting = false;
                    editSubmitBtn.disabled = false;
                    editSubmitBtn.innerHTML = '<i class="ri-save-line me-1"></i>Actualizar Categoría';
                });
            });
            
            console.log('✅ Script de edición de categorías inicializado correctamente');
            
        });
    </script>

@endpush