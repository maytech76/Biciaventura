@extends('layouts.app')

@section('title','Checkout')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>🛒 Checkout</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>

                            <li class="breadcrumb-item active">Checkout</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="checkout-section-2 section-b-space">
    <div class="container-fluid-lg">
        <div class="row g-sm-4 g-3">
            <div class="col-lg-8">
                <div class="left-sidebar-checkout">

                    <form action="{{ route('stripe.checkout') }}" method="POST">
                        @csrf
                        <div class="checkout-detail-box">
                            <ul>
                                <li>
                                    <div class="checkout-icon">
                                        <lord-icon target=".nav-item" src="../../ggihhudh.json" trigger="loop-on-hover" colors="primary:#121331,secondary:#646e78,tertiary:#0baf9a" class="lord-icon">
                                        </lord-icon>
                                    </div>

                                    <div class="checkout-box">
                                        <div class="checkout-title">
                                            <h4>Dirección de Entrega</h4>
                                        </div>

                                        <div class="checkout-detail">
                                            <div class="row g-4">
                                                @forelse ($addresses as $address)
                                                    <div class="col-xxl-6 col-lg-12 col-md-6">
                                                        <div class="delivery-address-box">
                                                            <div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="user_address_id"
                                                                        id="address-{{ $address->id }}" value="{{ $address->id }}" required>
                                                                </div>

                                                                <ul class="delivery-address-detail">
                                                                    <li><h4 class="fw-500">{{ $address->address }}</h4></li>
                                                                    <li><p class="text-content"><span class="text-title">Ciudad:</span> {{ $address->city }}</p></li>
                                                                    <li><h6 class="text-content"><span class="text-title">Estado:</span> {{ $address->state }}</h6></li>
                                                                    <li><h6 class="text-content"><span class="text-title">Código Postal:</span> {{ $address->postal_code }}</h6></li>
                                                                    <li><h6 class="text-content"><span class="text-title">País:</span> {{ $address->country }}</h6></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="col-12">
                                                        <p class="text-danger">No tienes direcciones registradas.</p>
                                                    </div>
                                                @endforelse

                                                <!-- Botón para mostrar el modal -->
                                                <div class="col-12">
                                                    <button type="button" class="btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold"
                                                        data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                                        Agregar nueva dirección
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </li>

                                <li>
                                    <div class="checkout-icon">
                                        <lord-icon target=".nav-item" src="../../oaflahpk.json" trigger="loop-on-hover" colors="primary:#0baf9a" class="lord-icon">
                                        </lord-icon>
                                    </div>
                                    <div class="checkout-box">
                                        <div class="checkout-title">
                                            <h4>Opción de Envió</h4>
                                        </div>

                                        <div class="checkout-detail">
                                            <div class="row g-4">
                                                <div class="col-xxl-12">
                                                    <div class="delivery-option">
                                                        <div class="delivery-category">
                                                            <div class="shipment-detail">
                                                                <div class="form-check custom-form-check hide-check-box">
                                                                    <input class="form-check-input" type="radio" name="standard" id="standard" checked="">
                                                                    <label class="form-check-label" for="standard">Envio Normal</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 future-box">
                                                    <div class="future-option">
                                                        <div class="row g-md-0 gy-4">
                                                            <div class="col-md-6">
                                                                <div class="delivery-items">
                                                                    <div>
                                                                        <h5 class="items text-content"><span>3
                                                                                Items</span>@
                                                                            $693.48</h5>
                                                                        <h5 class="charge text-content">Delivery Charge
                                                                            $34.67
                                                                            <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Extra Charge">
                                                                                <i class="fa-solid fa-circle-exclamation"></i>
                                                                            </button>
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <form class="form-floating theme-form-floating date-box">
                                                                    <input type="date" class="form-control">
                                                                    <label>Select Date</label>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="checkout-icon">
                                        <lord-icon target=".nav-item" src="../../qmcsqnle.json" trigger="loop-on-hover" colors="primary:#0baf9a,secondary:#0baf9a" class="lord-icon">
                                        </lord-icon>
                                    </div>
                                    <div class="checkout-box">
                                        <div class="checkout-detail">

                                            <button type="submit" class="btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold">
                                                Pagar con Stripe
                                            </button>

                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="right-side-summery-box">

                    @php
                        $shippingCost = 6.90;
                        $subTotal = $cartItems->sum('sub_total');
                        $total = $subTotal + $shippingCost;
                    @endphp

                    <div class="summery-box-2">
                        <div class="summery-header">
                            <h3>Detalle de Compra</h3>
                        </div>

                        <ul class="summery-contain">
                            @foreach ($cartItems as $item)
                                <li>
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid blur-up lazyloaded checkout-image" alt="{{ $item->product->name }}">
                                    <h4>{{ $item->product->name }} <span>X {{ $item->quantity }}</span></h4>
                                    <h4 class="price">$ {{ number_format($item->sub_total, 2) }}</h4>
                                </li>
                            @endforeach
                        </ul>

                        <ul class="summery-total">
                            <li>
                                <h4>Subtotal</h4>
                                <h4 class="price">$ {{ number_format($subTotal, 2) }}</h4>
                            </li>

                            <li>
                                <h4>Envio</h4>
                                <h4 class="price">$ {{ number_format($shippingCost, 2) }}</h4>
                            </li>
                            <br>

                            <li class="list-total">
                                <h4>Total (USD)</h4>
                                <h4 class="price">$ {{ number_format($total, 2) }}</h4>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Modal para agregar dirección -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('shipping.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Nueva Dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Dirección</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Ciudad</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Estado</label>
                        <input type="text" name="state" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Código Postal</label>
                        <input type="text" name="zip_code" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>País</label>
                        <input type="text" name="country" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold">Guardar Dirección</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
