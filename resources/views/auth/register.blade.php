@extends('layouts.app')

@section('title','Registrate')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2 class="mb-2">Registrate</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="index.html">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Registrate</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="log-in-section section-b-space">
    <div class="container-fluid-lg w-100">

        <div class="row">
            <!-- Imagen decorativa -->
            <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                <div class="image-contain">
                    <img src="{{ asset('assets/images/inner-page/sign-up.png') }}" class="img-fluid" alt="Registro en Andercode eCommerce">
                </div>
            </div>

            <!-- Formulario de registro -->
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div class="log-in-box">
                    <div class="log-in-title">
                        <h3>Bienvenido a Andercode eCommerce</h3>
                        <h4>Crea una nueva cuenta</h4>
                    </div>

                    <div class="input-box">
                        <form class="row g-4" method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Campo Nombre Completo -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nombre Completo" value="{{ old('name') }}" required>
                                    <label for="name">Nombre Completo</label>
                                    @error('name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Número de Teléfono" value="{{ old('phone') }}" required>
                                    <label for="phone">Número de Teléfono</label>
                                    @error('phone')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Campo Email -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                                    <label for="email">Correo Electrónico</label>
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Campo Contraseña -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Contraseña" required>
                                    <label for="password">Contraseña</label>
                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Campo Confirmar Contraseña -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Contraseña" required>
                                    <label for="password_confirmation">Confirmar Contraseña</label>
                                </div>
                            </div>

                            <!-- Aceptar Términos -->
                            <div class="col-12">
                                <div class="forgot-box">
                                    <div class="form-check ps-0 m-0 remember-box">
                                        <input class="checkbox_animated check-box" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Acepto los <span>Términos</span> y la <span>Política de Privacidad</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Registro -->
                            <div class="col-12">
                                <button class="btn btn-animation w-100" type="submit">Registrarse</button>
                            </div>
                        </form>
                    </div>

                    <!-- Separador -->
                    <div class="other-log-in">
                        <h6></h6>
                    </div>

                    <!-- Enlace a Iniciar Sesión -->
                    <div class="sign-up-box">
                        <h4>¿Ya tienes una cuenta?</h4>
                        <a href="{{ route('login') }}">Iniciar Sesión</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
