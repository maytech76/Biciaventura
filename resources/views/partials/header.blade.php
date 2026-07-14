<header class="header-2">

    {{-- <div class="header-notification theme-bg-color overflow-hidden py-2">
        
        <div class="notification-slider">
            <div>
                <div class="timer-notification text-center">
                    <h6>
                        <strong class="me-1">¡Bienvenido a Maydev!</strong>
                        Disfruta de nuevas ofertas y regalos todos los días en los fines de semana.
                        <strong class="ms-1">Muchas por Comprar</strong>
                    </h6>
                </div>
            </div>

            <div>
                <div class="timer-notification text-center">
                    <h6>
                        ¡Algo que te encanta ahora está en oferta!
                        <a href="{{ url('shop-left-sidebar') }}" class="text-white">¡Compra Ahora!</a>
                    </h6>
                </div>
            </div>
        </div>

        <!-- Botón para cerrar la notificación -->
        <button class="btn close-notification">
            <span>Cerrar</span> <i class="fas fa-times"></i>
        </button>
    </div> --}}

    <div class="top-nav top-header sticky-header sticky-header-3">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="navbar-top">
                        <a href="{{ route('home') }}" class="web-logo nav-logo">
                            <img src="{{ asset('assets/images/logo/maydev.png') }}" class="img-fluid blur-up lazyload" alt="" style="width: 75px;">
                        </a>

                        <div class="search-full">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search" class="font-light"></i>
                                </span>
                                <input type="text" class="form-control search-type" placeholder="Search here..">
                                <span class="input-group-text close-search">
                                    <i data-feather="x" class="font-light"></i>
                                </span>
                            </div>
                        </div>

                        <div class="middle-box">
                            <div class="center-box">
                                <div class="searchbar-box order-xl-1 d-none d-xl-block">
                                    <form action="{{ route('shop.index') }}" method="GET" class="search-form">
                                        <input type="text" name="search" class="form-control" placeholder="Buscar productos..."
                                            value="{{ request('search') }}">
                                        <button type="submit" class="btn search-button">
                                            <i class="iconly-Search icli"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="rightside-menu">

                            <div class="option-list">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)" class="header-icon user-icon search-icon">
                                            <i class="iconly-Profile icli"></i>
                                        </a>
                                    </li>

                                    <li>
                                        @auth
                                            <a href="{{ route('dashboard') }}" class="header-icon search-box search-icon">
                                                <i class="iconly-Profile icli"></i>
                                                <a>{{ Auth::user()->name }}</a>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="header-icon search-box search-icon">
                                                <i class="iconly-Profile icli"></i>
                                            </a>
                                        @endauth
                                    </li>


                                    <li class="onhover-dropdown">
                                        <a href="{{ route('wishlist.index') }}" class="header-icon swap-icon">
                                            <small id="wishlist-count" class="badge-number">0</small>
                                            <i class="iconly-Heart icli"></i>
                                        </a>
                                    </li>

                                    <li class="onhover-dropdown">
                                        <a href="{{ route('cart.index') }}" class="header-icon bag-icon">
                                            <small id="cart-count-uno" class="badge-number">0</small>
                                            <i class="iconly-Bag-2 icli"></i>
                                        </a>
                                        <div class="onhover-div">
                                            <ul class="cart-list" id="cart-dropdown">
                                                <!-- Los productos del carrito se insertarán aquí dinámicamente -->
                                            </ul>

                                            <div class="price-box">
                                                <h5>Total :</h5>
                                                <small>+Envio</small>
                                                <h4 class="theme-color fw-bold" id="cart-dropdown-total">$ 0.00</h4>
                                            </div>

                                            <div class="button-group">
                                                <a href="{{ route('cart.index') }}" class="btn btn-sm cart-button">Ver Carrito</a>
                                                <a href="{{ route('cart.checkout') }}" class="btn btn-sm cart-button theme-bg-color
                                                text-white">Proceder al Pago</a>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="onhover-dropdown">
                                        <a href="javascript:void(0)" class="header-icon swap-icon">
                                            <i class="iconly-Profile icli"></i>
                                        </a>
                                    </li>

                                    <li class="right-side onhover-dropdown">
                                        <div class="delivery-login-box">
                                            <div class="delivery-detail">
                                                <h6>Hola,</h6>
                                                <h5>{{ Auth::user()->name ?? 'Mi Cuenta'}}</h5>
                                            </div>
                                        </div>

                                        <div class="onhover-div onhover-div-login">
                                            <ul class="user-box-name">
                                                @auth
                                                    <!-- Si el usuario está autenticado -->
                                                    <li class="product-box-contain">
                                                        <a href="{{ route('dashboard') }}">Dashboard</a>
                                                    </li>

                                                    <li class="product-box-contain">
                                                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                                            @csrf
                                                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="text-danger">
                                                                Cerrar Sesión
                                                            </a>
                                                        </form>
                                                    </li>
                                                @else
                                                    <!-- Si el usuario no está autenticado -->
                                                    <li class="product-box-contain">
                                                        <a href="{{ route('login') }}">Iniciar Sesión</a>
                                                    </li>

                                                    <li class="product-box-contain">
                                                        <a href="{{ route('register') }}">Registrarse</a>
                                                    </li>
                                                @endauth
                                            </ul>
                                        </div>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
