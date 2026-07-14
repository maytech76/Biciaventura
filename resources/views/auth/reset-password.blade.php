@extends('layouts.app')

@section('title','Iniciar Session')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2 class="mb-2">Cambiar Contraseña</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="index.html">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Cambiar Contraseña</li>
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
                    <img src="{{ asset('assets/images/inner-page/forgot.png') }}" class="img-fluid" alt="Restablecer contraseña en Andercode eCommerce">
                </div>
            </div>

            <!-- Formulario de restablecimiento de contraseña -->
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h3>Restablece tu contraseña</h3>
                            <h4>Ingresa tu nueva contraseña</h4>
                        </div>

                        <!-- Manejo de errores globales -->
                        @if (session('status'))
                            <div class="alert alert-success text-center">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="input-box">
                            <form class="row g-4" method="POST" action="{{ route('password.store') }}">
                                @csrf

                                <!-- Token necesario para la recuperación -->
                                <input type="hidden" name="token" value="{{ request()->route('token') }}">

                                <!-- Campo Email -->
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" placeholder="Correo Electrónico"
                                            value="{{ old('email', request()->email) }}" required
                                        >
                                        <label for="email">Correo Electrónico</label>
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Campo Nueva Contraseña -->
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Nueva Contraseña" required>
                                        <label for="password">Nueva Contraseña</label>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Campo Confirmar Nueva Contraseña -->
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Contraseña" required>
                                        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                                    </div>
                                </div>

                                <!-- Botón de Restablecer -->
                                <div class="col-12">
                                    <button class="btn btn-animation w-100" type="submit">
                                        Restablecer Contraseña
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
