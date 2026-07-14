@extends('admin.layouts.master')

@section('title', 'Editar Categoría')

@section('content')
<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">
            <!-- Edit Category Start -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-8 m-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-header-2 w-100 mb-4 p-3" style="background-color: #f7f3f3 !important; border-radius:15px;">
                                            <h5 class="mb-0">Editar Categoría</h5>
                                        </div>

                                        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="theme-form theme-form-2 mega-form">

                                                {{-- Nombre de la categoria --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-sm-3 mb-0">Nombre</label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control @error('name') is-invalid @enderror" 
                                                               id="name" name="name" type="text"  
                                                               value="{{ old('name', $category->name) }}" 
                                                               required 
                                                               placeholder="Ingresar Nombre">
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Imagen Actual --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="col-sm-3 col-form-label form-label-title">Imagen Actual</label>
                                                    <div class="col-sm-9">
                                                        @if($category->photo)
                                                            <img src="{{ asset('storage/' . $category->photo) }}" 
                                                                 class="img-fluid rounded shadow-lg mb-2" 
                                                                 alt="{{ $category->name }}"
                                                                 style="max-height: 150px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                                                                <label class="form-check-label text-danger" for="remove_photo">
                                                                    Eliminar imagen actual
                                                                </label>
                                                            </div>
                                                        @else
                                                            <img src="{{ asset('assets/images/default-category.png') }}" 
                                                                 class="img-fluid rounded shadow-lg mb-2" 
                                                                 alt="Imagen por defecto"
                                                                 style="max-height: 150px;">
                                                            <p class="text-muted small">No hay imagen actual</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Nueva Imagen --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="col-sm-3 col-form-label form-label-title">Nueva Imagen</label>
                                                    <div class="form-group col-sm-9">
                                                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" id="photo" accept="image/*">
                                                        <small class="text-muted">Formatos: JPG, PNG, GIF (Max 2MB). Déjalo vacío para mantener la imagen actual.</small>
                                                        @error('photo')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Vista previa nueva imagen --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="col-sm-3 col-form-label form-label-title">Vista Previa</label>
                                                    <div class="col-sm-9">
                                                        <img id="photoPreview" src="#" alt="Vista previa" 
                                                             class="img-fluid rounded" style="display: none; max-height: 200px;">
                                                    </div>
                                                </div>

                                                {{-- Descripción --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-sm-3 mb-0">Descripción</label>
                                                    <div class="col-sm-9">
                                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                                  name="description" 
                                                                  id="description" 
                                                                  cols="30" 
                                                                  rows="2">{{ old('description', $category->description) }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Información adicional --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-sm-3 mb-0">Información</label>
                                                    <div class="col-sm-9">
                                                        <div class="card bg-light">
                                                            <div class="card-body py-2">
                                                                <small class="text-muted">
                                                                    <strong>Slug:</strong> {{ $category->slug }}<br>
                                                                    <strong>Creado:</strong> {{ $category->created_at->format('d/m/Y H:i') }}<br>
                                                                    <strong>Actualizado:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Botones -->
                                                <div class="row mt-4">
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <a href="{{ route('categories.index') }}" class="btn btn-secondary w-100">
                                                            <i class="ri-close-line"></i> Cancelar
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 mb-2">
                                                        <button type="submit" class="btn btn-success w-100">
                                                            <i class="ri-save-line px-1"></i> Actualizar Categoría
                                                        </button>
                                                    </div>
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
            <!-- Edit Category End -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Vista previa de la nueva imagen
    document.getElementById('photo').addEventListener('change', function(e) {
        const preview = document.getElementById('photoPreview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Confirmación antes de eliminar imagen
    document.getElementById('remove_photo')?.addEventListener('change', function(e) {
        if (this.checked) {
            if (!confirm('¿Estás seguro de que quieres eliminar la imagen actual?')) {
                this.checked = false;
            }
        }
    });
</script>
@endpush