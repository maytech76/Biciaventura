@extends('admin.layouts.master')

@section('content')
@section('title', 'Editar Evento')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-12 m-auto">
                        <div class="card">
                            <div class="card-body">

                                <div class="card-header">
                                    <h3 class="fw-bold text-warning mb-3">Edición del evento</h3>
                                </div>
                                

                                {{-- CORREGIDO: La acción debe incluir el ID y el método debe ser PUT --}}
                                <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data"
                                    class="theme-form theme-form-2 mega-form">
                                    @csrf
                                    @method('PUT') {{-- Cambiado de POST a PUT --}}
                                    <div class="row">

                                        {{-- Columna Izq --}}
                                        <div class="col-md-6 mt-4">

                                            {{-- Nombre del Evento --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 mb-0">Nombre</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control border" type="text"
                                                        id="name" name="name"
                                                        placeholder="Introduce el nombre del evento"
                                                        value="{{ old('name', $event->name) }}" required>
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- States --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 col-form-label">Estado</label>
                                                <div class="col-sm-9">
                                                    <select
                                                        class="js-example-basic-single w-100 form-select border"
                                                        name="state_id" id="state_id">
                                                        <option value="">Seleccione un Estado</option>
                                                        @foreach ($states as $state)
                                                            <option value="{{ $state->id }}"
                                                                {{ old('state_id', $event->state_id) == $state->id ? 'selected' : '' }}>
                                                                {{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('state_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Cities --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 col-form-label">Ciudad</label>
                                                <div class="col-sm-9">
                                                    <select
                                                        class="js-example-basic-single w-100 form-select border"
                                                        name="city_id" id="city_id">
                                                        <option value="">Seleccione una Ciudad</option>
                                                        @foreach ($cities as $city)
                                                            <option value="{{ $city->id }}"
                                                                {{ old('city_id', $event->city_id) == $city->id ? 'selected' : '' }}>
                                                                {{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('city_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Dirección del Evento --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 col-form-label">Dirección</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control border"
                                                        id="address"
                                                        placeholder="Ingresar dirección del evento con detalles"
                                                        name="address" data-role="tagsinput"
                                                        value="{{ old('address', $event->address) }}" required>
                                                    @error('address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Responsable --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 col-form-label">Responsable</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control border"
                                                        id="name_manager" name="name_manager"
                                                        placeholder="persona responsable del evento"
                                                        value="{{ old('name_manager', $event->name_manager) }}" required>
                                                    @error('name_manager')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Tipo de Evento --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 col-form-label">Tipo</label>
                                                <div class="col-sm-9">
                                                    <select name="type" id="type"
                                                        class="form-select border">
                                                        <option value="sport"
                                                            {{ old('type', $event->type) == 'sport' ? 'selected' : '' }}>Sport
                                                        </option>
                                                        <option value="mtb"
                                                            {{ old('type', $event->type) == 'mtb' ? 'selected' : '' }}>MTB</option>
                                                        <option value="route"
                                                            {{ old('type', $event->type) == 'route' ? 'selected' : '' }}>Route
                                                        </option>
                                                        <option value="downhill"
                                                            {{ old('type', $event->type) == 'downhill' ? 'selected' : '' }}>Downhill
                                                        </option>
                                                        <option value="enduro"
                                                            {{ old('type', $event->type) == 'enduro' ? 'selected' : '' }}>Enduro
                                                        </option>
                                                    </select>
                                                    @error('type')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Status del Evento --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 col-form-label">Status</label>
                                                <div class="col-sm-9">
                                                    <select name="status" id="status"
                                                        class="form-select border">
                                                        @foreach ($statusOptions as $value => $label)
                                                            <option value="{{ $value }}"
                                                                {{ old('status', $event->status) == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>

                                        {{-- Columna Derecha --}}
                                        <div class="col-md-6">

                                            <div class="card">
                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="col-12">

                                                            <div class="row align-items-center mb-3">
                                                                
                                                                <div class="col-sm-9">
                                                                    {{-- Mostrar banner actual --}}
                                                                    @if($event->banner)
                                                                        <div class="mb-2 align-items-center">
                                                                            <label class="col-sm-3 col-form-label fw-bold">
                                                                                Banner del Evento
                                                                            </label>
                                                                            <img src="{{ asset('storage/' . $event->banner) }}" 
                                                                                 alt="Banner actual" 
                                                                                 style="max-height: 100px; width: auto;" 
                                                                                 class="rounded">
                                                                            <br>
                                                                            <small class="text-muted">Banner actual</small>
                                                                        </div>
                                                                    @endif
                                                                    <input class="form-control form-choose"
                                                                           type="file" id="banner" name="banner"
                                                                           accept="image/*">
                                                                    <small class="text-muted">Dejar en blanco para mantener la imagen actual</small>
                                                                    @error('banner')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            {{-- Descripcion del Evento --}}
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="form-label-title col-sm-3 mb-0">Descripción</label>
                                                                <div class="col-sm-9">
                                                                    <textarea class="form-control border" id="description" name="description" rows="2"
                                                                        placeholder="ingresa una descripción detallada del evento" required>{{ old('description', $event->description) }}</textarea>
                                                                    @error('description')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            {{-- Fecha del Evento --}}
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="form-label-title col-sm-3 mb-0">Fecha del Evento</label>
                                                                <div class="col-sm-9">
                                                                    <input class="form-control border"
                                                                        type="date" id="event_date"
                                                                        name="event_date"
                                                                        value="{{ old('event_date', $event->event_date ? date('Y-m-d', strtotime($event->event_date)) : '') }}">
                                                                    @error('event_date')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            {{-- limite Inscripciones --}}
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="form-label-title col-sm-3 mb-0">Fecha Límite</label>
                                                                <div class="col-sm-9">
                                                                    <input class="form-control border"
                                                                        type="date" id="registration_deadline"
                                                                        name="registration_deadline"
                                                                        value="{{ old('registration_deadline', $event->registration_deadline ? date('Y-m-d', strtotime($event->registration_deadline)) : '') }}">
                                                                    @error('registration_deadline')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            {{-- email responsable --}}
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="form-label-title col-sm-3 mb-0">Email</label>
                                                                <div class="col-sm-9">
                                                                    <input class="form-control border"
                                                                        type="email" id="email_manager"
                                                                        name="email_manager"
                                                                        placeholder="email@ejemplo.com"
                                                                        value="{{ old('email_manager', $event->email_manager) }}">
                                                                    @error('email_manager')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            {{-- phone responsable --}}
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="form-label-title col-sm-3 mb-0">Celular</label>
                                                                <div class="col-sm-9">
                                                                    <input class="form-control border"
                                                                        type="text" id="phone" name="phone"
                                                                        placeholder="0412-1234567"
                                                                        value="{{ old('phone', $event->phone) }}">
                                                                    @error('phone')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <!-- Botones -->
                                    <div class="row mt-4">
                                        <div class="col-md-6 col-sm-12 mb-2">
                                            <a href="{{ route('events.index') }}" class="btn btn-secondary w-100">
                                                Cancelar
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-2">
                                            <button type="submit" class="btn btn-theme w-100">
                                                Actualizar
                                            </button>
                                        </div>
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

<style>
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

    .switch.sm-switch input:checked+.switch-state {
        background-color: #2ebe93;
    }

    .switch.sm-switch input:focus+.switch-state {
        box-shadow: 0 0 1px #2ebe93;
    }

    .switch.sm-switch input:checked+.switch-state:before {
        transform: translateX(20px);
    }
</style>
@endsection

@push('scripts')


{{--  Script para validar campos vacios y convertir a mayusculas --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        function validateAndUpperCase(input) {
            const errorSpan = document.getElementById(input.id + '-error');
            input.value = input.value.toUpperCase();
            
            if (input.value.trim() === '') {
                input.classList.add('is-invalid');
                if (!errorSpan) {
                    const span = document.createElement('span');
                    span.id = input.id + '-error';
                    span.className = 'text-danger';
                    span.textContent = 'Este campo no puede quedar vacío';
                    input.parentNode.appendChild(span);
                } else {
                    errorSpan.textContent = 'Este campo no puede quedar vacío';
                    errorSpan.style.display = 'block';
                }
                return false;
            } else {
                input.classList.remove('is-invalid');
                if (errorSpan) {
                    errorSpan.style.display = 'none';
                }
                return true;
            }
        }

        function validateOnInput(input) {
            input.value = input.value.toUpperCase();
            const errorSpan = document.getElementById(input.id + '-error');
            
            if (input.value.trim() === '') {
                input.classList.add('is-invalid');
                if (!errorSpan) {
                    const span = document.createElement('span');
                    span.id = input.id + '-error';
                    span.className = 'text-danger';
                    span.textContent = 'Este campo no puede quedar vacío';
                    input.parentNode.appendChild(span);
                } else {
                    errorSpan.textContent = 'Este campo no puede quedar vacío';
                    errorSpan.style.display = 'block';
                }
            } else {
                input.classList.remove('is-invalid');
                if (errorSpan) {
                    errorSpan.style.display = 'none';
                }
            }
        }

        const fields = ['name', 'address', 'description', 'name_manager'];
        
        fields.forEach(function(fieldId) {
            const input = document.getElementById(fieldId);
            if (input) {
                input.addEventListener('input', function() {
                    validateOnInput(this);
                });
                input.addEventListener('blur', function() {
                    validateAndUpperCase(this);
                });
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        validateAndUpperCase(this);
                    }
                });
                input.addEventListener('paste', function(e) {
                    setTimeout(() => {
                        this.value = this.value.toUpperCase();
                        validateOnInput(this);
                    }, 100);
                });
            }
        });

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                fields.forEach(function(fieldId) {
                    const input = document.getElementById(fieldId);
                    if (input) {
                        input.value = input.value.toUpperCase();
                        if (input.value.trim() === '') {
                            input.classList.add('is-invalid');
                            const errorSpan = document.getElementById(input.id + '-error');
                            if (!errorSpan) {
                                const span = document.createElement('span');
                                span.id = input.id + '-error';
                                span.className = 'text-danger';
                                span.textContent = 'Este campo no puede quedar vacío';
                                input.parentNode.appendChild(span);
                            } else {
                                errorSpan.textContent = 'Este campo no puede quedar vacío';
                                errorSpan.style.display = 'block';
                            }
                            isValid = false;
                        } else {
                            input.classList.remove('is-invalid');
                            const errorSpan = document.getElementById(input.id + '-error');
                            if (errorSpan) {
                                errorSpan.style.display = 'none';
                            }
                        }
                    }
                });
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¡Campos incompletos!',
                        text: 'Por favor, completa todos los campos obligatorios.',
                        icon: 'warning',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }

        setInterval(function() {
            fields.forEach(function(fieldId) {
                const input = document.getElementById(fieldId);
                if (input && input.value.length > 0) {
                    const currentValue = input.value;
                    const upperValue = currentValue.toUpperCase();
                    if (currentValue !== upperValue) {
                        input.value = upperValue;
                    }
                }
            });
        }, 1000);

    });
</script>

{{-- Script para filtrado de ciudades por estado --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Elementos del DOM
        const stateSelect = document.getElementById('state_id');
        const citySelect = document.getElementById('city_id');
        
        // Guardar todas las ciudades originales (para referencia)
        const allCities = @json($cities);
        
        // Función para filtrar ciudades por estado
        function filterCitiesByState(stateId) {
            // Limpiar el select de ciudades
            citySelect.innerHTML = '<option value="">Seleccione una Ciudad</option>';
            
            if (!stateId) {
                return;
            }
            
            // Filtrar ciudades que pertenecen al estado seleccionado
            const filteredCities = allCities.filter(city => city.state_id == stateId);
            
            // Si no hay ciudades para este estado
            if (filteredCities.length === 0) {
                citySelect.innerHTML = '<option value="">No hay ciudades disponibles</option>';
                return;
            }
            
            // Agregar las ciudades filtradas al select
            filteredCities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                // Mantener el valor seleccionado si existe en old()
                if ({{ old('city_id') ? 'true' : 'false' }} && city.id == {{ old('city_id') ?? 'null' }}) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });
        }
        
        // Evento cuando cambia el estado
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            filterCitiesByState(stateId);
        });
        
        // Si hay un estado preseleccionado (cuando el formulario vuelve con errores)
        if (stateSelect.value) {
            filterCitiesByState(stateSelect.value);
        }
        
    });
</script>


@endpush