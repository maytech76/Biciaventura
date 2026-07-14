@extends('admin.layouts.master')

@section('title', 'Recibo de Pago')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-receipt"></i> RECIBO DE PAGO</h4>
                        <button onclick="window.print()" class="btn btn-light btn-sm">
                            <i class="bi bi-printer"></i> Imprimir
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Encabezado -->
                    <div class="text-center mb-4">
                        <h4 class="display-5">Nombre de la Empresa</h4>
                        <h3 class="text-primary">{{ $payment->receipt_number }}</h3>
                        <p class="text-muted">Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <!-- Información del Recibo -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong>INFORMACIÓN DEL PAGO</strong>
                                </div>
                                <div class="card-body">
                                    <p><strong>Número de Recibo:</strong> {{ $payment->receipt_number }}</p>
                                    <p><strong>Fecha de Pago:</strong> {{ $payment->payment_date->format('d/m/Y') }}</p>
                                    <p><strong>Método de Pago:</strong> {{ ucfirst($payment->payment_method) }}</p>
                                    @if($payment->reference)
                                    <p><strong>Referencia:</strong> {{ $payment->reference }}</p>
                                    @endif
                                    @if($payment->comments)
                                    <p><strong>Observaciones:</strong> {{ $payment->comments }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong>INFORMACIÓN DE LA CUENTA</strong>
                                </div>
                                <div class="card-body">
                                    <p><strong>Número de CXC:</strong> {{ $payment->receivable->code }}</p>
                                    <p><strong>Cliente:</strong> {{ $payment->receivable->user->name }}</p>
                                    <p><strong>Concepto:</strong> {{ $payment->receivable->concept }}</p>
                                    <p><strong>Saldo Anterior:</strong> 
                                       ${{ number_format($payment->receivable->pending_balance + $payment->amount, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detalle del Pago -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white">Descripción</th>
                                    <th class="text-white">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Pago parcial de cuenta {{ $payment->receivable->code }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                </tr>
                                @if($payment->comments)
                                <tr>
                                    <td colspan="2">
                                        <strong>Observación:</strong> {{ $payment->comments }}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <th class="text-end">TOTAL PAGADO:</th>
                                    <th>${{ number_format($payment->amount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th class="text-end">NUEVO SALDO:</th>
                                    <th>${{ number_format($payment->receivable->pending_balance, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Firmas -->
                    <div class="row mt-5">
                        <div class="col-md-5 text-center">
                            <hr>
                            <p>___________________________</p>
                            <p><strong>Firma del Cliente</strong></p>
                            <p>{{ $payment->receivable->user->name }}</p>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-5 text-center">
                            <hr>
                            <p>___________________________</p>
                            <p><strong>Firma del Administrador</strong></p>
                            <p>{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                    
                    <!-- Mensaje Informativo -->
                    <div class="alert alert-light mt-4 text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            Este recibo es válido como comprobante de pago. 
                            Conserve este documento para cualquier consulta.
                        </small>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('receivables.show', $payment->receivable) }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Volver a la Cuenta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        .btn {
            display: none !important;
        }
    }
</style>
@endsection