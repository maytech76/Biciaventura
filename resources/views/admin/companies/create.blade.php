{{-- resources/views/admin/companies/create.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Registrar Compañía')

@section('content')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">
            <div class="container-fluid">
                
                {{-- Verificar si ya existe una empresa registrada --}}
                @if($companyExists)
                    {{-- Mostrar mensaje de empresa ya registrada --}}
                    <div class="row min-vh-100 align-items-center justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6">
                            <div class="text-center">
                                <div class="mb-4">
                                    {{-- Icono de advertencia --}}
                                    <div class="display-1 text-warning mb-4">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    
                                    {{-- Mensaje principal --}}
                                    <h1 class="display-4 fw-bold text-warning mb-3">
                                        ¡EMPRESA YA REGISTRADA!
                                    </h1>
                                    
                                    {{-- Mensaje secundario --}}
                                    <p class="lead text-muted mb-4">
                                        Ya existe una empresa registrada en el sistema. 
                                        
                                    </p>
                                    
                                    {{-- Información de la empresa existente --}}
                                    @if(isset($existingCompany))
                                    <div class="card bg-light mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title text-dark w-200">
                                                
                                                Licencia Asignada a:
                                            </h5>
                                            <p class="card-text">
                                                <strong class="text-muted">{{ $existingCompany->name }}</strong><br>
                                                <small class="text-muted">
                                                    RIF: {{ $existingCompany->doc }} | 
                                                    Email: {{ $existingCompany->email }}
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    {{-- Botones de acción --}}
                                   {{--  <div class="d-flex justify-content-center gap-3">
                                        <a href="{{ route('companies.index') }}" class="btn btn-primary btn-lg px-4">
                                            <i class="fas fa-eye me-2"></i>
                                            Ver datos de la empresa
                                        </a>
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">
                                            <i class="fas fa-tachometer-alt me-2"></i>
                                            Ir al Dashboard
                                        </a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Mostrar formulario de registro si no hay empresa --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-building me-2"></i>Datos de la empresa
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <div class="row">
                                            <!-- Left Column: Logo and Status -->
                                            <div class="col-md-4 mb-4">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <!-- Company Logo -->
                                                        <div class="mb-3">
                                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3 mx-auto"
                                                                style="width: 180px; height: 180px; border: 4px solid #dee2e6; overflow: hidden;">
                                                                <img src="{{ asset('img/default-company.png') }}" 
                                                                    alt="Company logo preview" 
                                                                    class="img-fluid"
                                                                    style="width: 100%; height: 100%; object-fit: cover; display: none;"
                                                                    id="logoPreview">
                                                                <i class="fas fa-building text-secondary fa-4x" id="logoPlaceholder"></i>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="logo" class="form-label fw-bold">
                                                                    <i class="fas fa-upload me-1"></i>Logo de la empresa
                                                                </label>
                                                                <input type="file" 
                                                                    class="form-control @error('logo') is-invalid @enderror" 
                                                                    id="logo" 
                                                                    name="logo"
                                                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                                                    onchange="previewLogo(this)">
                                                                @error('logo')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                                <div class="form-text text-muted">
                                                                    <i class="fas fa-info-circle me-1"></i>
                                                                    Formatos: JPG, PNG, GIF. Tamaño máximo: 2MB
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Company Status -->
                                                        <div class="mt-4 p-3 bg-light rounded">
                                                            <label class="fw-bold mb-2">
                                                                <i class="fas fa-toggle-on me-1"></i>Estado de la empresa
                                                            </label>
                                                            <div class="d-flex justify-content-center gap-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" 
                                                                        type="radio" 
                                                                        name="status" 
                                                                        id="statusActive" 
                                                                        value="active"
                                                                        {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                                                    <label class="form-check-label text-success" for="statusActive">
                                                                        <i class="fas fa-check-circle me-1"></i>Activo
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" 
                                                                        type="radio" 
                                                                        name="status" 
                                                                        id="statusInactive" 
                                                                        value="inactive"
                                                                        {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                                                    <label class="form-check-label text-danger" for="statusInactive">
                                                                        <i class="fas fa-times-circle me-1"></i>Inactivo
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @error('status')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                
                                            <!-- Right Column: Company Information -->
                                            <div class="col-md-8">
                                                <div class="row">
                                                   
                                                    <!-- Document Number (NIT/RUC) -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="doc" class="form-label fw-bold">
                                                            Numero de Rif: <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-id-card"></i>
                                                            </span>
                                                            <input type="text" 
                                                                class="form-control @error('doc') is-invalid @enderror" 
                                                                id="doc" 
                                                                name="doc" 
                                                                value="{{ old('doc') }}"
                                                                required
                                                                maxlength="15"
                                                                placeholder="Ej: 123456789-0">
                                                        </div>
                                                        @error('doc')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                        <div class="form-text">Máximo 15 caracteres</div>
                                                    </div>
                
                                                    <!-- Company Name -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="name" class="form-label fw-bold">
                                                            Nombre de la Empresa <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-building"></i>
                                                            </span>
                                                            <input type="text" 
                                                                class="form-control @error('name') is-invalid @enderror" 
                                                                id="name" 
                                                                name="name" 
                                                                value="{{ old('name') }}"
                                                                required
                                                                maxlength="150"
                                                                placeholder="Ej: Mi Empresa S.A.S.">
                                                        </div>
                                                        @error('name')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Email -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="email" class="form-label fw-bold">
                                                            Correo Electrónico <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-envelope"></i>
                                                            </span>
                                                            <input type="email" 
                                                                class="form-control @error('email') is-invalid @enderror" 
                                                                id="email" 
                                                                name="email" 
                                                                value="{{ old('email') }}"
                                                                required
                                                                maxlength="150"
                                                                placeholder="Ej: contacto@miempresa.com">
                                                        </div>
                                                        @error('email')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Phone -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="phone" class="form-label fw-bold">
                                                            Teléfono
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-phone"></i>
                                                            </span>
                                                            <input type="text" 
                                                                class="form-control @error('phone') is-invalid @enderror" 
                                                                id="phone" 
                                                                name="phone" 
                                                                value="{{ old('phone') }}"
                                                                maxlength="20"
                                                                placeholder="Ej: +57 123 456 7890">
                                                        </div>
                                                        @error('phone')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Website -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="website" class="form-label fw-bold">
                                                            Sitio Web
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-globe"></i>
                                                            </span>
                                                            <input type="url" 
                                                                class="form-control @error('website') is-invalid @enderror" 
                                                                id="website" 
                                                                name="website" 
                                                                value="{{ old('website') }}"
                                                                maxlength="150"
                                                                placeholder="Ej: https://www.miempresa.com">
                                                        </div>
                                                        @error('website')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Location Section -->
                                                    <div class="col-12 mb-3 mt-3">
                                                        <h6 class="border-bottom pb-2 mb-3">
                                                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                            Ubicación
                                                        </h6>
                                                    </div>
                
                                                    <!-- Address -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="address" class="form-label fw-bold">
                                                            Dirección
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-location-dot"></i>
                                                            </span>
                                                            <input type="text" 
                                                                class="form-control @error('address') is-invalid @enderror" 
                                                                id="address" 
                                                                name="address" 
                                                                value="{{ old('address') }}"
                                                                maxlength="200"
                                                                placeholder="Ej: Calle 123 #45-67">
                                                        </div>
                                                        @error('address')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- City -->
                                                    <div class="col-md-3 mb-3">
                                                        <label for="city" class="form-label fw-bold">
                                                            Ciudad
                                                        </label>
                                                        <input type="text" 
                                                            class="form-control @error('city') is-invalid @enderror" 
                                                            id="city" 
                                                            name="city" 
                                                            value="{{ old('city') }}"
                                                            maxlength="150"
                                                            placeholder="Ej: Bogotá">
                                                        @error('city')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Country -->
                                                    <div class="col-md-3 mb-3">
                                                        <label for="country" class="form-label fw-bold">
                                                            País
                                                        </label>
                                                        <input type="text" 
                                                            class="form-control @error('country') is-invalid @enderror" 
                                                            id="country" 
                                                            name="country" 
                                                            value="{{ old('country') }}"
                                                            maxlength="150"
                                                            placeholder="Ej: Colombia">
                                                        @error('country')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                
                                        <!-- Action buttons -->
                                        <div class="row mt-4 g-2">
                                            <div class="col-12 col-md-4">
                                                <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary w-100">
                                                    <i class="fas fa-arrow-left me-1"></i> Volver datos de la empresa
                                                </a>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <button type="button" class="btn btn-outline-warning w-100" onclick="resetForm()">
                                                    <i class="fas fa-undo me-1"></i> Limpiar formulario
                                                </button>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-save me-1"></i> Registrar
                                                </button>
                                            </div>
                                        </div>
                
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Función para previsualizar el logo antes de subirlo
    function previewLogo(input) {
        const preview = document.getElementById('logoPreview');
        const placeholder = document.getElementById('logoPlaceholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }

    // Función para limpiar el formulario
    function resetForm() {
        if(confirm('¿Estás seguro de limpiar todos los campos?')) {
            document.querySelector('form').reset();
            // Resetear previsualización de imagen
            document.getElementById('logoPreview').style.display = 'none';
            document.getElementById('logoPlaceholder').style.display = 'block';
        }
    }
</script>
@endpush