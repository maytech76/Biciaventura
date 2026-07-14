<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Despacho - #{{ $order->id }}</title>
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
        
        /* CONTENEDOR PRINCIPAL */
        .dispatch-container {
            width: 21cm;
            min-height: 29.7cm;
            margin: 0 auto;
            padding: 1cm 1.5cm;
            background: white;
            position: relative;
        }
        
        /* CABECERA */
        .dispatch-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #e74c3c;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }
        
        .dispatch-title {
            text-align: right;
        }
        
        .dispatch-title h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 5px;
        }
        
        .dispatch-number {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        /* INFORMACIÓN DE LA ORDEN */
        .order-info-section {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-box {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .info-header {
            background: linear-gradient(to right, #3498db, #2980b9);
            color: white;
            padding: 8px 15px;
            font-weight: 600;
            font-size: 12px;
        }
        
        .info-content {
            padding: 15px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dotted #eee;
        }
        
        .info-label {
            flex: 0 0 120px;
            font-weight: 600;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        /* TABLA DE PRODUCTOS */
        .products-section {
            margin: 25px 0;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .products-table th {
            background: linear-gradient(to right, #2c3e50, #34495e);
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #2c3e50;
        }
        
        .products-table td {
            padding: 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
        
        .product-info h4 {
            margin: 0 0 5px 0;
            font-size: 13px;
        }
        
        .product-info p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }
        
        /* RESUMEN DE TOTALES */
        .totals-section {
            margin-top: 30px;
            text-align: right;
        }
        
        .totals-box {
            display: inline-block;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            min-width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .total-row:last-child {
            border-bottom: none;
        }
        
        .total-row.grand-total {
            background: #f8f9fa;
            font-weight: 700;
            font-size: 14px;
            color: #e74c3c;
        }
        
        /* INFORMACIÓN DE ENVÍO */
        .shipping-section {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }
        
        .shipping-header {
            font-weight: 600;
            color: #3498db;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .shipping-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .shipping-item {
            margin-bottom: 10px;
        }
        
        .shipping-item strong {
            display: block;
            color: #555;
            margin-bottom: 3px;
        }
        
        /* FIRMAS */
        .signatures-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 2px dashed #ddd;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            width: 80%;
            height: 1px;
            background: #333;
            margin: 30px auto 10px;
        }
        
        .signature-name {
            font-weight: 600;
            margin-top: 5px;
        }
        
        .signature-role {
            font-size: 11px;
            color: #666;
        }
        
        /* INSTRUCCIONES DE DESPACHO */
        .instructions-section {
            margin-top: 30px;
            padding: 15px;
            border: 1px dashed #f39c12;
            border-radius: 5px;
            background: #fffbf0;
        }
        
        .instructions-header {
            font-weight: 600;
            color: #f39c12;
            margin-bottom: 10px;
        }
        
        .instructions-list {
            list-style: none;
            padding-left: 0;
        }
        
        .instructions-list li {
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }
        
        .instructions-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #27ae60;
            font-weight: bold;
        }
        
        /* ESTILOS PARA IMPRESIÓN */
        @media print {
            @page {
                size: A4;
                margin: 0.5cm;
            }
            
            body {
                margin: 0;
                padding: 0;
                background: white;
                font-size: 11px;
            }
            
            .dispatch-container {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            
            .no-print {
                display: none !important;
            }
            
            /* Mejorar calidad de impresión */
            .products-table th,
            .products-table td {
                padding: 6px 8px;
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
            }
            
            .dispatch-container {
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                border-radius: 8px;
                overflow: hidden;
                background: white;
            }
            
            .action-buttons {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                display: flex;
                gap: 10px;
            }
            
            .btn {
                padding: 10px 20px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s ease;
                font-size: 13px;
            }
            
            .btn-print {
                background: #3498db;
                color: white;
                box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
            }
            
            .btn-back {
                background: #e74c3c;
                color: white;
                box-shadow: 0 4px 6px rgba(231, 76, 60, 0.3);
            }
            
            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 8px rgba(0,0,0,0.2);
            }
        }
        
        /* BADGES PARA ESTADOS */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 5px;
        }
        
        .badge-pending { background: #f39c12; color: white; }
        .badge-processing { background: #3498db; color: white; }
        .badge-shipped { background: #27ae60; color: white; }
        .badge-delivered { background: #2ecc71; color: white; }
        .badge-cancelled { background: #e74c3c; color: white; }
        
        /* AJUSTES RESPONSIVE */
        @media (max-width: 21cm) {
            .dispatch-container {
                width: 100%;
                padding: 15px;
            }
            
            .dispatch-header {
                flex-direction: column;
            }
            
            .dispatch-title {
                text-align: left;
                margin-top: 15px;
            }
            
            .order-info-section {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Botones de acción -->
    <div class="action-buttons no-print">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Imprimir
        </button>
        <a href="{{ route('orders.index') }}" class="btn btn-back">
            ← Volver
        </a>
    </div>
    
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="dispatch-container">
        <!-- CABECERA -->
        <div class="dispatch-header">
            <div class="company-info">
                <div class="company-name">TIENDA ONLINE</div>
                <div class="company-details">
                    RUC: 12345678901<br>
                    Av. Principal 123, Lima, Perú<br>
                    Tel: (01) 234-5678 | Email: ventas@tiendaonline.com
                </div>
            </div>
            
            <div class="dispatch-title">
                <h1>ORDEN DE DESPACHO</h1>
                <div class="dispatch-number">
                    N°: {{ $dispatchInfo['dispatch_number'] }}
                </div>
                <div style="font-size: 11px; color: #666; margin-top: 5px;">
                    Fecha: {{ $dispatchInfo['dispatch_date']->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
        
        <!-- INFORMACIÓN DE LA ORDEN -->
        <div class="order-info-section">
            <div class="info-box">
                <div class="info-header">INFORMACIÓN DEL CLIENTE</div>
                <div class="info-content">
                    <div class="info-row">
                        <div class="info-label">Cliente:</div>
                        <div class="info-value">{{ $order->user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $order->user->email }}</div>
                    </div>
                    @if($order->user->phone)
                    <div class="info-row">
                        <div class="info-label">Teléfono:</div>
                        <div class="info-value">{{ $order->user->phone }}</div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label">Orden ID:</div>
                        <div class="info-value">
                            #{{ $order->id }}
                            <span class="badge badge-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="info-box">
                <div class="info-header">INFORMACIÓN DEL PEDIDO</div>
                <div class="info-content">
                    <div class="info-row">
                        <div class="info-label">Fecha Orden:</div>
                        <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Fecha Despacho:</div>
                        <div class="info-value">{{ $dispatchInfo['dispatch_date']->format('d/m/Y') }}</div>
                    </div>
                    @if($order->payment)
                    <div class="info-row">
                        <div class="info-label">Pago:</div>
                        <div class="info-value">
                            {{ ucfirst($order->payment->payment_method ?? 'No especificado') }}
                            @if($order->payment->status == 'completed')
                            <span class="badge badge-delivered">Pagado</span>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label">Preparado por:</div>
                        <div class="info-value">{{ $dispatchInfo['prepared_by'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- PRODUCTOS -->
        <div class="products-section">
            <h3 style="color: #2c3e50; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 5px;">
                📦 PRODUCTOS A DESPACHAR
            </h3>
            
            <table class="products-table">
                <thead>
                    <tr>
                        <th width="60">Imagen</th>
                        <th>Producto</th>
                        <th width="80" class="text-center">Cantidad</th>
                        <th width="100" class="text-right">Precio Unit.</th>
                        <th width="100" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    @php
                        $product = $item->product;
                    @endphp
                    <tr>
                        <td class="text-center">
                            @if($product && $product->main_image_url)
                                <img src="{{ $product->main_image_url }}" 
                                     class="product-image" 
                                     alt="{{ $product->name }}">
                            @else
                                <div style="width: 60px; height: 60px; background: #f5f5f5; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #999; font-size: 10px;">SIN IMAGEN</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="product-info">
                                <h4>{{ $product->name ?? 'Producto no disponible' }}</h4>
                                @if($product && $product->sku)
                                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                                @endif
                                @if($product && $product->description)
                                    <p style="font-size: 10px; margin-top: 3px;">{{ Str::limit($product->description, 100) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <strong>{{ $item->quantity }}</strong>
                        </td>
                        <td class="text-right">
                            ${{ number_format($item->price, 2) }}
                        </td>
                        <td class="text-right">
                            <strong>${{ number_format($item->subtotal, 2) }}</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- RESUMEN DE TOTALES -->
        <div class="totals-section">
            <div class="totals-box">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                
                @if($totals['discount'] > 0)
                <div class="total-row">
                    <span>Descuento:</span>
                    <span style="color: #27ae60;">-${{ number_format($totals['discount'], 2) }}</span>
                </div>
                @endif
                
                @if($totals['shipping'] > 0)
                <div class="total-row">
                    <span>Envío:</span>
                    <span>${{ number_format($totals['shipping'], 2) }}</span>
                </div>
                @endif
                
                @if($totals['tax'] > 0)
                <div class="total-row">
                    <span>Impuestos:</span>
                    <span>${{ number_format($totals['tax'], 2) }}</span>
                </div>
                @endif
                
                <div class="total-row grand-total">
                    <span>TOTAL:</span>
                    <span>${{ number_format($totals['total'], 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- INFORMACIÓN DE ENVÍO -->
        @if($order->userAddress || $order->shippingAddress)
        <div class="shipping-section">
            <div class="shipping-header">📍 INFORMACIÓN DE ENVÍO</div>
            <div class="shipping-details">
                @if($order->shippingAddress)
                <div class="shipping-item">
                    <strong>Dirección de Envío:</strong>
                    {{ $order->shippingAddress->address }}
                </div>
                @if($order->shippingAddress->zone)
                <div class="shipping-item">
                    <strong>Zona/Región:</strong>
                    {{ $order->shippingAddress->zone->name }}
                    @if($order->shippingAddress->zone->city)
                    , {{ $order->shippingAddress->zone->city->name }}
                    @endif
                </div>
                @endif
                @elseif($order->userAddress)
                <div class="shipping-item">
                    <strong>Dirección del Cliente:</strong>
                    {{ $order->userAddress->address }}
                </div>
                @endif
                
                @if($order->user->phone)
                <div class="shipping-item">
                    <strong>Teléfono Contacto:</strong>
                    {{ $order->user->phone }}
                </div>
                @endif
                
                <div class="shipping-item">
                    <strong>Método de Entrega:</strong>
                    Envío a domicilio
                </div>
            </div>
        </div>
        @endif
        
        <!-- INSTRUCCIONES DE DESPACHO -->
        <div class="instructions-section">
            <div class="instructions-header">📋 INSTRUCCIONES PARA EL DESPACHO</div>
            <ul class="instructions-list">
                <li>Verificar que todos los productos estén incluidos según la lista</li>
                <li>Asegurar que los productos estén en perfecto estado</li>
                <li>Incluir factura y guía de remisión</li>
                <li>Verificar dirección de entrega antes de salir</li>
                <li>Confirmar entrega con firma del cliente</li>
            </ul>
        </div>
        
        <!-- FIRMAS -->
        <div class="signatures-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $order->user->name }}</div>
                <div class="signature-role">CLIENTE / RECEPTOR</div>
            </div>
            
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ auth()->user()->name }}</div>
                <div class="signature-role">PERSONAL DE DESPACHO</div>
            </div>
        </div>
        
        <!-- PIE DE PÁGINA -->
        <div style="margin-top: 40px; padding-top: 15px; border-top: 2px solid #eee; text-align: center; color: #666; font-size: 10px;">
            <p><strong>TIENDA ONLINE - Sistema de Gestión de Pedidos</strong></p>
            <p style="margin-top: 5px;">
                Este documento es válido como orden de despacho. Conserve una copia para control interno.
            </p>
            <p style="margin-top: 5px;">
                Generado el: {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
    
    <!-- SCRIPT PARA IMPRESIÓN -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-imprimir si se solicita
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('print')) {
                setTimeout(() => {
                    window.print();
                    setTimeout(() => {
                        // Opcional: volver después de imprimir
                        // window.history.back();
                    }, 1000);
                }, 500);
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
        });
    </script>
</body>
</html>