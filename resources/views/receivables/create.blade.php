@extends('admin.layouts.master')

@section('content')
@section('title', 'Nueva Cuenta')

<!-- New Product Add Start -->
<div class="min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="card shadow-lg">
                <div class="card-body py-4">
                    <div class="card-header border-0 bg-transparent text-center">
                        <h3 class="fw-bold text-primary">Registro de Cuentas x Cobrar</h3>
                        <p class="text-muted mb-0">Complete los datos de la cuenta por cobrar</p>
                    </div>
                    
                    <form action="{{ route('receivables.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label fw-semibold">Cliente *</label>
                                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                        <option value="">Seleccionar cliente...</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="concept" class="form-label fw-semibold">Concepto *</label>
                                    <input type="text" class="form-control @error('concept') is-invalid @enderror" 
                                           id="concept" name="concept" value="{{ old('concept') }}" 
                                           placeholder="Ej: Venta de mercadería" required>
                                    @error('concept')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Descripción detallada...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="total_amount" class="form-label fw-semibold">Monto Total *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0.01" 
                                           class="form-control @error('total_amount') is-invalid @enderror" 
                                           id="total_amount" name="total_amount" 
                                           value="{{ old('total_amount') }}" required>
                                </div>
                                @error('total_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label fw-semibold">Fecha de Vencimiento</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date') }}"
                                       min="{{ date('Y-m-d') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('receivables.index') }}" class="btn btn-secondary me-md-2 px-4">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i> Guardar Cuenta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection