<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago - {{ $payment->receipt_number }}</title>
    <style>
        /* ESTILOS GENERALES */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            background: #fff;
            font-size: 12px;
        }
        
        /* CONTENEDOR PRINCIPAL - COMPACTO HORIZONTAL */
        .receipt-container {
            width: 18cm;           /* ANCHO REDUCIDO para impresión horizontal */
            min-height: 29.7cm;    /* Alto carta estándar */
            margin: 0 auto;
            padding: 0.8cm 1cm;    /* Padding reducido */
            background: white;
            position: relative;
        }
        
        /* CABECERA HORIZONTAL ELEGANTE */
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
            position: relative;
        }
        
        /* COLUMNA IZQUIERDA - EMPRESA */
        .company-column {
            flex: 0 0 60%; /* 60% del ancho */
            padding-right: 20px;
        }
        
        .company-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 20px; /* Reducido */
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .company-info {
            font-size: 10px; /* Reducido */
            color: #666;
            line-height: 1.3;
        }
        
        /* COLUMNA DERECHA - INFORMACIÓN DEL RECIBO */
        .receipt-column {
            flex: 0 0 40%; /* 40% del ancho */
            text-align: right;
            border-left: 1px solid #e9ecef;
            padding-left: 20px;
        }
        
        .receipt-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 20px; /* Reducido */
            font-weight: 500;
            color: #e78207;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.1px;
        }
        
        .receipt-number {
            font-size: 18px; /* Reducido */
            font-weight: 600;
            color: #dc3545;
            background: #f8f9fa;
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
           /*  border: 2px dashed #dc3545; */
            margin-bottom: 8px;
        }
        
        .receipt-meta {
            font-size: 10px;
            color: #666;
        }
        
        /* LÍNEA DIVISORIA ELEGANTE */
        .header-divider {
            display: flex;
            align-items: center;
            margin: 10px 0;
            color: #6c757d;
            font-size: 10px;
        }
        
        .header-divider:before,
        .header-divider:after {
            content: "";
            flex: 1;
            border-bottom: 1px dashed #dee2e6;
        }
        
        .header-divider:before {
            margin-right: 10px;
        }
        
        .header-divider:after {
            margin-left: 10px;
        }
        
        /* INFORMACIÓN DEL RECIBO - DISEÑO COMPACTO */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin: 15px 0; /* Margen reducido */
            gap: 15px;
        }
        
        .info-box {
            flex: 1;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .info-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 8px 12px;
            border-bottom: 1px solid #007bff;
            font-weight: 600;
            color: #2c3e50;
            font-size: 12px;
        }
        
        .info-content {
            padding: 12px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 6px; /* Reducido */
            padding-bottom: 6px;
            border-bottom: 1px dotted #e9ecef;
            font-size: 11px;
        }
        
        .info-label {
            flex: 0 0 120px; /* Ancho fijo reducido */
            font-weight: 600;
            color: #495057;
        }
        
        .info-value {
            flex: 1;
            color: #212529;
        }
        
        /* TABLA COMPACTA */
        .details-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
            font-size: 11px;
        }
        
        .details-table th {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            font-weight: 600;
            padding: 8px 10px;
            text-align: left;
            border-right: 1px solid #0056b3;
        }
        
        .details-table th:last-child {
            border-right: none;
        }
        
        .details-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        
        /* TOTALES COMPACTOS */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin: 20px 0;
        }
        
        .totals-box {
            width: 250px; /* Ancho reducido */
            border: 1px solid #007bff;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .total-row-display {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }
        
        /* FIRMAS COMPACTAS */
        .signatures-section {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 11px;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            width: 70%; /* Ancho reducido */
            height: 1px;
            background: #333;
            margin: 25px auto 8px;
            position: relative;
        }
        
        .signature-line:before {
            content: "";
            position: absolute;
            top: -15px;
            left: 0;
            right: 0;
            height: 30px;
            border-bottom: 1px solid #333;
        }
        
        /* ESTILOS PARA IMPRESIÓN */
        @media print {
            @page {
                size: A4;
                margin: 0.5cm; /* Márgenes reducidos */
            }
            
            body {
                margin: 0;
                padding: 0;
                background: white;
                font-size: 10px;
            }
            
            .receipt-container {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            
            .no-print {
                display: none !important;
            }
            
            /* Ajustes específicos para impresión compacta */
            .receipt-header {
                padding-bottom: 10px;
                margin-bottom: 15px;
            }
            
            .info-section {
                margin: 10px 0;
            }
            
            /* Asegurar que no se corte contenido */
            .page-break {
                page-break-before: always;
            }
        }
        
        /* ESTILOS PARA NAVEGADOR */
        @media screen {
            body {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                padding: 20px;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .receipt-container {
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                border-radius: 8px;
                overflow: hidden;
                background: white;
            }
            
            .print-button {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                background: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                font-size: 13px;
            }
            
            .back-button {
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1000;
                background: #e78207;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                text-decoration: none;
                display: inline-block;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                font-size: 13px;
            }
        }
        
        /* ELEMENTOS DE DISEÑO */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-info {
            background: #17a2b8;
            color: white;
        }
        
        /* ESTILOS RESPONSIVE */
        @media (max-width: 18cm) {
            .receipt-container {
                width: 100%;
                padding: 15px;
            }
            
            .receipt-header {
                flex-direction: column;
            }
            
            .company-column, .receipt-column {
                flex: 1;
                width: 100%;
                padding: 0;
                border: none;
                text-align: left;
            }
            
            .receipt-column {
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid #e9ecef;
            }
        }
    </style>
</head>
<body>
    <!-- Botones de acción -->
    <a href="{{ route('receivables.show', $payment->receivable) }}" class="back-button no-print">
        ← Volver
    </a>
    
    <button onclick="window.print()" class="print-button no-print">
        🖨️ Imprimir
    </button>
    
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="receipt-container">
        <!-- CABECERA HORIZONTAL -->
        <div class="receipt-header">
            <!-- COLUMNA IZQUIERDA - EMPRESA -->
            <div class="company-column">
                <div class="company-name">EMPRESA COMERCIAL S.A.</div>
                <div class="company-info">
                    RUC: 12345678901<br>
                    Av. Principal 123, Lima, Perú<br>
                    Tel: (01) 234-5678 | Email: info@empresa.com
                </div>
            </div>
            
            <!-- COLUMNA DERECHA - RECIBO -->
            <div class="receipt-column">
                <div class="receipt-title">RECIBO DE PAGO</div>
                <div class="receipt-number">{{ $payment->receipt_number }}</div>
                <div class="receipt-meta">
                    Fecha: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
        
        <!-- LÍNEA DIVISORIA -->
        <div class="header-divider">
            COMPROBANTE DE PAGO
        </div>
        
        <!-- INFORMACIÓN DEL PAGO Y CUENTA -->
        <div class="info-section">
            <!-- Información del Pago -->
            <div class="info-box">
                <div class="info-header">DATOS DEL PAGO</div>
                <div class="info-content">
                    <div class="info-row">
                        <div class="info-label">Recibo:</div>
                        <div class="info-value">{{ $payment->receipt_number }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Fecha:</div>
                        <div class="info-value">{{ $payment->payment_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Método:</div>
                        <div class="info-value">
                            {{ ucfirst($payment->payment_method) }}
                            @if($payment->reference)
                            <span class="badge badge-info ml-2">Ref: {{ $payment->reference }}</span>
                            @endif
                        </div>
                    </div>
                    @if($payment->comments)
                    <div class="info-row">
                        <div class="info-label">Observación:</div>
                        <div class="info-value">{{ $payment->comments }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Información de la Cuenta -->
            <div class="info-box">
                <div class="info-header">DATOS DE LA CUENTA</div>
                <div class="info-content">
                    <div class="info-row">
                        <div class="info-label">CXC:</div>
                        <div class="info-value">{{ $payment->receivable->code }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Cliente:</div>
                        <div class="info-value">{{ $payment->receivable->user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Concepto:</div>
                        <div class="info-value">{{ Str::limit($payment->receivable->concept, 50) }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Saldo Ant.:</div>
                        <div class="info-value">
                            ${{ number_format($payment->receivable->pending_balance + $payment->amount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- DETALLE DEL PAGO -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 70%;">Descripción</th>
                    <th style="width: 30%; text-align: right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Pago de cuenta {{ $payment->receivable->code }}</strong><br>
                        <small>{{ $payment->receivable->concept }}</small>
                    </td>
                    <td style="text-align: right; font-weight: 600;">
                        ${{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="background: #f8f9fa;">
                    <td style="text-align: right; font-weight: 600; padding: 10px;">
                        TOTAL PAGADO:
                    </td>
                    <td style="text-align: right; font-weight: 700; font-size: 13px; padding: 10px; color: #28a745;">
                        ${{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        
        <!-- RESUMEN FINANCIERO -->
        <div class="totals-section">
            <div class="totals-box">
                <div class="total-row-display">
                    <span>Saldo Anterior:</span>
                    <span>${{ number_format($payment->receivable->pending_balance + $payment->amount, 2) }}</span>
                </div>
                <div class="total-row-display">
                    <span>Monto Pagado:</span>
                    <span>-${{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="total-row-display" style="background: #e9ecef; font-weight: 600;">
                    <span>NUEVO SALDO:</span>
                    <span style="color: {{ $payment->receivable->pending_balance > 0 ? '#dc3545' : '#28a745' }};">
                        ${{ number_format($payment->receivable->pending_balance, 2) }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- FIRMAS -->
        <div class="signatures-section">
            <div class="signature-box">
                {{-- <div class="signature-line"></div> --}}
                <div class="signature-name">{{ $payment->receivable->user->name }}</div>
                <div class="signature-role">CLIENTE</div>
            </div>
            
            <div class="signature-box">
                {{-- <div class="signature-line"></div> --}}
                <div class="signature-name">{{ auth()->user()->name }}</div>
                <div class="signature-role">ADMINISTRADOR</div>
            </div>
        </div>
        
        <!-- PIE DE PÁGINA -->
        <div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #dee2e6; text-align: center; color: #666; font-size: 9px;">
            <p>Documento válido como comprobante de pago • Conserve este recibo para cualquier consulta</p>
            <p style="margin-top: 5px;">Sistema E-Commerce 2026 • {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
    
    <!-- SCRIPT PARA IMPRESIÓN -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración para impresión
            const printStyles = `
                @media print {
                    @page {
                        size: A4 portrait;
                        margin: 0.5cm;
                    }
                    
                    body {
                        font-size: 10px !important;
                    }
                    
                    .receipt-container {
                        width: 100% !important;
                        padding: 0 !important;
                    }
                }
            `;
            
            const styleSheet = document.createElement('style');
            styleSheet.textContent = printStyles;
            document.head.appendChild(styleSheet);
            
            // Auto-imprimir si se solicita
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('autoPrint')) {
                setTimeout(() => window.print(), 500);
            }
            
            // Atajos de teclado
            document.addEventListener('keydown', function(e) {
                // Ctrl+P para imprimir
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
                
                // Escape para volver
                if (e.key === 'Escape') {
                    window.history.back();
                }
            });
            
            // Botón de impresión con confirmación
            const printButton = document.querySelector('.print-button');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    if (confirm('¿Está listo para imprimir el recibo?\n\nAsegúrese de que la impresora tenga papel.')) {
                        window.print();
                    }
                });
            }
        });
    </script>
</body>
</html>