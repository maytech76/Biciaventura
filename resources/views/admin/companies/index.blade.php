{{-- resources/views/admin/companies/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Compañía')

@section('content')


    <div class="compact-wrapper">
        <div class="page-body-wrapper">
            <div class="page-body">
                <div class="container-fluid">
                    
                    {{-- Mostrar botón de registro SOLO si no hay compañías --}}
                    @if(!$company)
                    <div class="row min-vh-100 align-items-center justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6">
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="fas fa-building fa-4x text-muted mb-3"></i>
                                    <h3 class="mb-3">¡Bienvenido!</h3>
                                    <p class="text-muted mb-4">Aún no has registrado ninguna empresa. Comienza registrando los datos de tu compañía.</p>
                                </div>
                                <a href="{{ route('companies.create') }}" class="btn btn-success btn-lg w-100 py-3 shadow-lg">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Registrar Empresa
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    
                    {{-- Mostrar formulario con datos de la empresa si ya existe --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-building me-2"></i>
                                        Datos de la empresa: <strong>{{ $company->name }}</strong>
                                    </h3>
                                
                                </div>
                                <div class="card-body">
                                    {{-- Formulario para ver/editar empresa --}}
                                    <form action="{{ route('companies.update', $company->id) }}" 
                                        method="POST" 
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row">

                                            <!-- Left Column: Logo and Status -->
                                            <div class="col-md-4 mb-4">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <!-- Company Logo -->
                                                        <div class="mb-3">
                                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3 mx-auto"
                                                                style="width: 180px; height: 180px; border: 4px solid #dee2e6; overflow: hidden;">
                                                                @if($company->logo)
                                                                    <img src="{{ asset('storage/' . $company->logo) }}" 
                                                                        alt="{{ $company->name }}" 
                                                                        class="img-fluid"
                                                                        style="width: 100%; height: 100%; object-fit: cover;"
                                                                        id="logoPreview">
                                                                    <i class="fas fa-building text-secondary fa-4x" id="logoPlaceholder" style="display: none;"></i>
                                                                @else
                                                                    <img src="{{ asset('img/default-company.png') }}" 
                                                                        alt="Company logo preview" 
                                                                        class="img-fluid"
                                                                        style="width: 100%; height: 100%; object-fit: cover; display: none;"
                                                                        id="logoPreview">
                                                                    <i class="fas fa-building text-secondary fa-4x" id="logoPlaceholder"></i>
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="logo" class="form-label fw-bold">
                                                                    <i class="fas fa-image"></i>Logo de la empresa
                                                                </label>
                                                                {{-- INPUT DE LOGO CON READONLY --}}
                                                               {{--  <input type="file" 
                                                                    class="form-control @error('logo') is-invalid @enderror" 
                                                                    id="logo" 
                                                                    name="logo"
                                                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                                                    onchange="previewLogo(this)"
                                                                    readonly> --}}
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
                                                    
                                                    </div>
                                                </div>
                                            </div>
                
                                            <!-- Right Column: Company Information -->
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <!-- Document Number (NIT/RUC) -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="doc" class="form-label fw-bold mb-0">
                                                            DOCUMENTO: <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group mt-0">
                                                            <span class="input-group-text bg-transparent border-0">
                                                                <i class="fas fa-id-card"></i>
                                                            </span>
                                                            <input type="text" 
                                                                class="text-muted form-control @error('doc') is-invalid @enderror bg-transparent border-0" 
                                                                id="doc" 
                                                                name="doc" 
                                                                value="{{ old('doc', $company->doc) }}"
                                                                maxlength="15"
                                                                placeholder="Ej: J-12345678-9"
                                                                readonly>
                                                        </div>
                                                        @error('doc')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Company Name -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="name" class="form-label fw-bold mb-0">
                                                            NOMBRE DE LA EMPRESA <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group mt-0">
                                                            <span class="input-group-text bg-transparent border-0">
                                                                <i class="fas fa-building"></i>
                                                            </span>
                                                            {{-- INPUT NAME CON READONLY --}}
                                                            <input type="text" 
                                                                class="text-muted form-control @error('name') is-invalid @enderror bg-transparent border-0" 
                                                                id="name" 
                                                                name="name" 
                                                                value="{{ old('name', $company->name) }}"
                                                                maxlength="150"
                                                                placeholder="Ej: Mi Empresa S.A.S."
                                                                readonly>
                                                        </div>
                                                        @error('name')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Email -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="email" class="form-label fw-bold mb-0">
                                                            CORREO ELECTRÓNICO <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group mt-0">
                                                            <span class="input-group-text bg-transparent border-0">
                                                                <i class="fas fa-envelope"></i>
                                                            </span>
                                                            {{-- INPUT EMAIL CON READONLY --}}
                                                            <input type="email" 
                                                                class="text-muted form-control @error('email') is-invalid @enderror bg-transparent border-0" 
                                                                id="email" 
                                                                name="email" 
                                                                value="{{ old('email', $company->email) }}"
                                                                maxlength="150"
                                                                placeholder="Ej: contacto@miempresa.com"
                                                                readonly>
                                                        </div>
                                                        @error('email')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Phone -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="phone" class="form-label fw-bold mb-0">
                                                            TELEFONO
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-transparent border-0 mt-0">
                                                                <i class="fas fa-phone"></i>
                                                            </span>
                                                            {{-- INPUT PHONE CON READONLY --}}
                                                            <input type="text" 
                                                                class="text-muted form-control @error('phone') is-invalid @enderror bg-transparent border-0" 
                                                                id="phone" 
                                                                name="phone" 
                                                                value="{{ old('phone', $company->phone) }}"
                                                                maxlength="20"
                                                                placeholder="Ej: +58 212 1234567"
                                                                readonly>
                                                        </div>
                                                        @error('phone')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Website -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="website" class="form-label fw-bold mb-0">
                                                            WEBSITE
                                                        </label>
                                                        <div class="input-group mt-0">
                                                            <span class="input-group-text bg-transparent border-0">
                                                                <i class="fas fa-globe"></i>
                                                            </span>
                                                            {{-- INPUT WEBSITE CON READONLY --}}
                                                            <input type="url" 
                                                                class="text-muted form-control @error('website') is-invalid @enderror bg-transparent border-0" 
                                                                id="website" 
                                                                name="website" 
                                                                value="{{ old('website', $company->website) }}"
                                                                maxlength="150"
                                                                placeholder="Ej: https://www.miempresa.com"
                                                                readonly>
                                                        </div>
                                                        @error('website')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Lisense -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="website" class="form-label fw-bold mb-0">
                                                            N° DE LICENCIA
                                                        </label>
                                                        <div class="input-group mt-0">
                                                            <span class="input-group-text bg-transparent border-0">
                                                                <i class="fas fa-key"></i>
                                                            </span>
                                                            {{-- INPUT WEBSITE CON READONLY --}}
                                                            <input type="url" 
                                                                class="form-control @error('website') is-invalid @enderror bg-transparent border-0" 
                                                                id="website" style="font-size: 15px; color: #fa3b2a;"
                                                                name="website" 
                                                                value="{{ old('license', $company->license) }}"
                                                                maxlength="15 "
                                                                readonly>
                                                        </div>
                                                        @error('lisense')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                           
                
                                                    <!-- Address -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="address" class="form-label fw-bold mb-0">
                                                            DIRECCIÓN
                                                        </label>
                                                        <div class="input-group mt-0">
                                                            
                                                            {{-- INPUT ADDRESS CON READONLY --}}
                                                            <input type="text" 
                                                                class="text-muted form-control @error('address') is-invalid @enderror bg-transparent border-0" 
                                                                id="address" 
                                                                name="address" 
                                                                value="{{ old('address', $company->address) }}"
                                                                maxlength="20"
                                                                placeholder="Ej: Av. Principal, Edif. Empresa, Piso 1"
                                                                readonly>
                                                        </div>
                                                        @error('address')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- City -->
                                                    <div class="col-md-3 mb-3">
                                                        <label for="city" class="form-label fw-bold mb-0">
                                                            CIUDAD
                                                        </label>
                                                        {{-- INPUT CITY CON READONLY --}}
                                                        <input type="text" 
                                                            class="text-muted form-control @error('city') is-invalid @enderror bg-transparent border-0 mt-0" 
                                                            id="city" 
                                                            name="city" 
                                                            value="{{ old('city', $company->city) }}"
                                                            maxlength="150"
                                                            placeholder="Ej: Caracas"
                                                            readonly>
                                                        @error('city')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                
                                                    <!-- Country -->
                                                    <div class="col-md-3 mb-3">
                                                        <label for="country" class="form-label fw-bold mb-0">
                                                            PAÍS
                                                        </label>
                                                        {{-- INPUT COUNTRY CON READONLY --}}
                                                        <input type="text" 
                                                            class="text-muted form-control @error('country') is-invalid @enderror bg-transparent border-0 mt-0" 
                                                            id="country" 
                                                            name="country" 
                                                            value="{{ old('country', $company->country) }}"
                                                            maxlength="150"
                                                            placeholder="Ej: Venezuela"
                                                            readonly>
                                                        @error('country')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
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
                if (placeholder) placeholder.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Script adicional para manejar la confirmación de actualización (opcional)
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form && form.action.includes('update')) {
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Estás seguro de actualizar los datos de la empresa?')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endpush