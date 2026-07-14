@extends('layouts.app')

@section('title','Carrito')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>🛒 Mi Carrito de Compras</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>

                            <li class="breadcrumb-item active">Carrito de Compras</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cart-section section-b-space">
    <div class="container-fluid-lg">
        <div class="row g-sm-5 g-3">
            <!-- Tabla de productos en el carrito -->
            <div class="col-xxl-9">
                <div class="cart-table">
                    <div class="table-responsive-xl">
                        <table class="table">
                            <tbody id="cart-container">
                                <!-- Los productos se insertarán aquí dinámicamente desde LocalStorage -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen del carrito -->
            <div class="col-xxl-3">
                <div class="summery-box p-sticky">
                    <div class="summery-header">
                        <h3>Total del Carrito</h3>
                    </div>

                    <div class="summery-contain">
                        <ul>
                            <li>
                                <h4>Subtotal</h4>
                                <h4 class="price" id="subtotal">$0.00</h4>
                            </li>

                            <li class="align-items-start">
                                <h4>Envío</h4>
                                <h4 class="price text-end">$6.90</h4>
                            </li>
                        </ul>
                    </div>

                    <ul class="summery-total">
                        <li class="list-total border-top-0">
                            <h4>Total (USD)</h4>
                            <h4 class="price theme-color" id="total">$0.00</h4>
                        </li>
                    </ul>

                    <div class="button-group cart-button">
                        <ul>
                            <li>
                                <button class="btn btn-animation proceed-btn fw-bold" onclick="window.location.href='{{ route('cart.checkout') }}'">
                                    Proceder al Pago
                                </button>
                            </li>

                            <li>
                                <button class="btn btn-light shopping-button text-dark" onclick="location.href = '/'">
                                    <i class="fa-solid fa-arrow-left-long"></i> Seguir Comprando
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
