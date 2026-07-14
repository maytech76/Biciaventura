@extends('layouts.app')

@section('title','Error en el Pago')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>🛒 Compra Fallida</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>

                            <li class="breadcrumb-item active">Compra Fallida</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain breadcrumb-order">
                    <div class="order-box">
                        <div class="col-12">
                            <div class="image-404">
                                <img src="../assets/images/inner-page/404.png" class="img-fluid blur-up lazyload" alt="">
                            </div>
                        </div>

                        <div class="order-contain">
                            <h3 class="theme-color">Compra Fallida</h3>
                            <h5 class="text-content">El pago no se ha realizado, por favor intenta nuevamente.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
