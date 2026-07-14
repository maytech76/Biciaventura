@extends('admin.layouts.master')

@section('title', 'Detalles de Cuenta')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('receivables.index') }}">Cuentas por Cobrar</a></li>
            <li class="breadcrumb-item active">{{ $receivable->code }}</li>
        </ol>
    </nav>
    
    <div class="row pt-5">
        
        <!-- Información Principal y Panel Lateral-->
        <div class="col-md-8">
            
            <div class="card border border-secondary mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $receivable->code }} - {{ $receivable->concept }}</h4>
                    <span class="text-white badge bg-{{ 
                        $receivable->status == 'pagado' ? 'primary' : 
                        ($receivable->status == 'pendiente' ? 'warning' : 
                        ($receivable->status == 'retrazo' ? 'danger' : 'info')) 
                    }} fs-6">
                        {{ ucfirst($receivable->status) }}
                        @if($receivable->isOverdue())
                            ({{ $receivable->days_overdue }} días de retraso)
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="bi bi-person"></i> Cliente:</strong><br>
                            {{ $receivable->user->name }}<br>
                            <small class="text-muted">{{ $receivable->user->email }}</small></p>
                            
                            <p><strong><i class="bi bi-calendar-event"></i> Fecha Emisión:</strong><br>
                            {{ $receivable->issue_date->format('d/m/Y') }}</p>
                            
                            <p><strong><i class="bi bi-calendar-x"></i> Fecha Vencimiento:</strong><br>
                            @if($receivable->due_date)
                                {{ $receivable->due_date->format('d/m/Y') }}
                                @if($receivable->due_date->isPast())
                                    <span class="badge bg-danger">Vencido</span>
                                @endif
                            @else
                                <span class="text-muted">No definida</span>
                            @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="bi bi-currency-dollar"></i> Monto Total:</strong><br>
                            <span class="fs-4">${{ number_format($receivable->total_amount, 2) }}</span></p>
                            
                            <p><strong><i class="bi bi-cash-stack"></i> Saldo Pendiente:</strong><br>
                            <span class="fs-4 {{ $receivable->pending_balance > 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($receivable->pending_balance, 2) }}
                            </span></p>
                            
                            <p><strong><i class="bi bi-percent"></i> Pagado:</strong><br>
                            <span class="fs-4 text-success">{{ number_format($receivable->payment_percentage, 1) }}%</span></p>
                        </div>
                    </div>
                    
                    @if($receivable->description)
                    <div class="mt-3">
                        <strong><i class="bi bi-card-text"></i> Descripción:</strong>
                        <p class="mt-2">{{ $receivable->description }}</p>
                    </div>
                    @endif
                    
                    <!-- Barra de Progreso -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Progreso de Pago</span>
                            <span>{{ number_format($receivable->payment_percentage, 1) }}%</span>
                        </div>

                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $receivable->payment_percentage }}%">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-1">
                            <small>${{ number_format($receivable->paid_amount, 2) }} pagado</small>
                            <small>${{ number_format($receivable->pending_balance, 2) }} pendiente</small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
            
        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Acciones -->
            <div class="card border border-secondary mb-4">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-lightning-charge"></i> Acciones</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($receivable->pending_balance > 0)
                        <a href="{{ route('receivables.payment.create', $receivable) }}" 
                        class="btn btn-success btn-lg">
                            <i class="bi bi-cash-coin"></i> Registrar Pago
                        </a>
                        @endif
                        
                        <a href="{{ route('receivables.edit', $receivable) }}" 
                        class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Editar Cuenta
                        </a>
                        
                        <form action="{{ route('receivables.destroy', $receivable) }}" method="POST" 
                            onsubmit="return confirm('¿Está seguro de eliminar esta cuenta?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn w-100 text-white" style="background-color: red">
                                <i class="bi bi-trash"></i> Eliminar Cuenta
                            </button>
                        </form>

                        <a href="{{ route('receivables.index') }}" 
                        class="btn btn-info">
                            <i class="bi bi-pencil"></i> Listado CxC
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Información Adicional -->
            <div class="card border border-secondary">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-info-circle"></i> Información</h4>
                </div>
                <div class="card-body">
                    <p><strong>Estado Actual:</strong><br>
                    @if($receivable->status == 'pagado')
                        <span class="text-success">✓ Completamente pagado</span>
                    @elseif($receivable->status == 'retrazo')
                        <span class="text-danger">✗ Vencido - Atención requerida</span>
                    @elseif($receivable->status == 'parcial')
                        <span class="text-info">↔ Pago parcial realizado</span>
                    @else
                        <span class="text-warning">⏳ Pendiente de pago</span>
                    @endif
                    </p>
                    
                    @if($receivable->due_date)
                    <p><strong>Días Restantes/Vencidos:</strong><br>
                    @if($receivable->due_date->isFuture())
                        {{ now()->diffInDays($receivable->due_date) }} días restantes
                    @elseif($receivable->pending_balance > 0)
                        {{ now()->diffInDays($receivable->due_date) }} días vencidos
                    @endif
                    </p>
                    @endif
                    
                    <p><strong>Último Pago:</strong><br>
                    @if($receivable->payments->count() > 0)
                        {{ $receivable->payments->last()->payment_date->format('d/m/Y') }}
                    @else
                        <span class="text-muted">Sin pagos</span>
                    @endif
                    </p>
                </div>
            </div>

        </div>
            
        
    </div>
        
        
    <div class="row pb-4">
        <!-- Historial de Pagos -->
        <div class="col-md-12">
            
            <div class="card border border-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Pagos</h4>
                    @if($receivable->pending_balance > 0)
                    <a href="{{ route('receivables.payment.create', $receivable) }}" 
                       class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle"></i> Nuevo Pago
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($receivable->payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Recibo</th>
                                    <th>Documento</th>
                                    <th>Fecha</th>
                                    <th>Método</th>
                                    <th>Monto</th>
                                    <th>Referencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($receivable->payments as $payment)
                                <tr>
                                    <td><strong>{{ $payment->receipt_number }}</strong></td>
                                    <td>{{ $receivable->code }}</td>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->reference ?? 'N/A' }}</td>
                                    <td>
                                        {{-- <a href="{{ route('payments.receipt', $payment) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-receipt"></i> Recibo
                                        </a> --}}

                                        <a href="{{ route('payments.receiptweb', $payment) }}" class="btn btn-primary btn-sm" target="_blank"><i class="bi bi-receipt"></i> Recibo
                                        </a>


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan="4"><strong class="text-white">Total Pagado</strong></td>
                                    <td colspan="4"><strong class="text-white">${{ number_format($receivable->paid_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-cash-coin fs-1"></i>
                        <p class="mt-2">No hay pagos registrados</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection