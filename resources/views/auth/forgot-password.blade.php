@extends('layouts.app')

@section('title','Iniciar Session')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2 class="mb-2">Olvidaste tu contraseña</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="index.html">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Olvidaste tu contraseña</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="log-in-section section-b-space forgot-section">
    <div class="container-fluid-lg w-100">

        <div class="row">
            <!-- Imagen decorativa -->
            <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                <div class="image-contain">
                    <img src="{{ asset('assets/images/inner-page/forgot.png') }}" class="img-fluid" alt="Recuperar contraseña en Andercode eCommerce">
                </div>
            </div>

            <!-- Formulario de recuperación de contraseña -->
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h3>Recupera tu acceso a Andercode eCommerce</h3>
                            <h4>Ingresa tu correo para restablecer tu contraseña</h4>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success text-center">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="input-box">
                            <form class="row g-4" method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <!-- Campo Email -->
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required autofocus>
                                        <label for="email">Correo Electrónico</label>
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Botón Enviar -->
                                <div class="col-12">
                                    <button class="btn btn-animation w-100" type="submit">
                                        Enviar enlace de recuperación
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Enlace para volver a inicio de sesión -->
                        <div class="sign-up-box mt-3 text-center">
                            <h4>¿Recordaste tu contraseña?</h4>
                            <a href="{{ route('login') }}">Iniciar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
