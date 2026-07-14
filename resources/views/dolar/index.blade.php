@extends('admin.layouts.master')

@section('title', 'Ciudades')

@section('content')

<div class="compact-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-4">Tasas de Cambio - Venezuela</h1>
                        
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0">Tasa Actual - Dólar Oficial</h3>
                            </div>
                            <div class="card-body text-center">
                                @if(isset($tasaActual))
                                    <div class="display-1 fw-bold text-primary">
                                        Bs. {{ number_format($tasaActual->promedio, 2) }}
                                    </div>
                                    <hr>
                                    <div class="mt-3 text-muted">
                                        <small>
                                            <strong>Actualización API:</strong> 
                                            {{ \Carbon\Carbon::parse($tasaActual->fecha_actualizacion_api)->format('d/m/Y H:i:s') }}
                                            <br>
                                            <strong>Registro en sistema:</strong> 
                                            {{ $tasaActual->fecha_registro->format('d/m/Y H:i:s') }}
                                        </small>
                                    </div>
                                @else
                                    <p>No hay información disponible</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h3 class="mb-0">Historial (Últimos 7 días)</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                             <tr>
                                                <th>Fecha/Hora</th>
                                                <th>Tasa (Bs.)</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($historial as $registro)
                                             <tr>
                                                <td>{{ $registro->fecha_registro->format('d/m/Y H:i') }}</td>
                                                <td><strong>{{ number_format($registro->promedio, 2) }}</strong></td>
                                             </tr>
                                            @empty
                                             <tr>
                                                <td colspan="2" class="text-center">No hay historial</td>
                                             </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection