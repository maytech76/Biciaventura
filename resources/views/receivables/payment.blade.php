@extends('admin.layouts.master')

@section('content')
@section('title', 'Registrar Pago')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('receivables.index') }}">Cuentas por Cobrar</a></li>
            <li class="breadcrumb-item"><a href="{{ route('receivables.show', $receivable) }}">{{ $receivable->code }}</a></li>
            <li class="breadcrumb-item active">Registrar Pago</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-none">
                    <h4 class="mb-0 fw-bold w-200 text-dark bg-none">Registrar Pago</h4>
                </div>
                <div class="card-body">
                    <!-- Resumen de la Cuenta -->
                    <div class="rounded p-4 mb-2" style="background-color:powderblue">
                        <h5 class="text-dark"> Resumen de la Cuenta</h5>
                        <p class="text-dark"><strong>Cliente:</strong> {{ $receivable->user->name }}</p>
                        <p class="text-dark"><strong>Concepto:</strong> {{ $receivable->concept }}</p>
                        <p class="text-dark"><strong>Saldo Pendiente:</strong> 
                           <span class="fs-5 text-dark">${{ number_format($receivable->pending_balance, 2) }}</span>
                        </p>
                    </div>
                    
                    <!-- Formulario de Pago -->
                    <form action="{{ route('receivables.payments.store', $receivable) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Monto a Pagar *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0.01" 
                                       max="{{ $receivable->pending_balance }}"
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" 
                                       value="{{ old('amount', $receivable->pending_balance) }}"
                                       required>
                            </div>
                            <small class="text-muted">Máximo: ${{ number_format($receivable->pending_balance, 2) }}</small>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Fecha de Pago *</label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" 
                                   value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Método de Pago *</label>
                            <select name="payment_method" id="payment_method" 
                                    class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="efectivo" {{ old('payment_method') == 'efectivo' ? 'selected' : '' }}>
                                    Efectivo
                                </option>
                                <option value="debito" {{ old('payment_method') == 'debito' ? 'selected' : '' }}>
                                    Tarjeta Débito
                                </option>
                                <option value="credito" {{ old('payment_method') == 'credito' ? 'selected' : '' }}>
                                    Tarjeta Crédito
                                </option>
                                <option value="transferencia" {{ old('payment_method') == 'transferencia' ? 'selected' : '' }}>
                                    Transferencia Bancaria
                                </option>
                                <option value="pmovil" {{ old('payment_method') == 'pmovil' ? 'selected' : '' }}>
                                    Pago Móvil
                                </option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="reference" class="form-label">Referencia/Comprobante</label>
                            <input type="text" class="form-control @error('reference') is-invalid @enderror" 
                                   id="reference" name="reference" value="{{ old('reference') }}"
                                   placeholder="Número de transacción, transferencia, etc.">
                            @error('reference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="comments" class="form-label">Comentarios/Observaciones</label>
                            <textarea class="form-control @error('comments') is-invalid @enderror" 
                                      id="comments" name="comments" rows="3">{{ old('comments') }}</textarea>
                            @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('receivables.show', $receivable) }}" 
                               class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Registrar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validar que el monto no exceda el saldo
    document.getElementById('amount').addEventListener('change', function() {
        const maxAmount = parseFloat('{{ $receivable->pending_balance }}');
        const inputAmount = parseFloat(this.value);
        
        if (inputAmount > maxAmount) {
            alert('El monto no puede exceder el saldo pendiente de $' + maxAmount.toFixed(2));
            this.value = maxAmount.toFixed(2);
        }
    });
</script>
@endpush
@endsection