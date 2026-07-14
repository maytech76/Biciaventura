<!DOCTYPE html>
<html lang="es">
<head>
    <title>@yield('title', 'Ecommerce')</title>
    @include('partials.head')
</head>

<body class="theme-color-2 bg-effect">

    <!-- Loader Start -->
    @include('partials.loader')
    <!-- Loader End -->

    <!-- Header Start -->
    @include('partials.header')
    <!-- Header End -->

    <!-- mobile fix menu start -->
    <div class="mobile-menu d-md-none d-block mobile-cart">
        <ul>
            <li>
                <a href="{{ route('home') }}">
                    <i class="iconly-Home icli"></i>
                    <span>Inicio</span>
                </a>
            </li>

            <li>
                <a href="{{ route('shop.index') }}" class="search-box">
                    <i class="iconly-Search icli"></i>
                    <span>Buscar</span>
                </a>
            </li>

            <li>
                <a href="{{ route('wishlist.index') }}" class="notifi-wishlist">
                    <i class="iconly-Heart icli"></i>
                    <span>Mi Lista</span>
                </a>
            </li>

            <li>
                <a href="{{ route('cart.index') }}">
                    <i class="iconly-Bag-2 icli fly-cate"></i>
                    <span>Mi Carrito</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- mobile fix menu end -->

    @yield('content')

    <!-- Footer Start -->
    @include('partials.footer')
    <!-- Footer End -->

    <!-- Items section Start -->
    <div class="button-item">
        <button class="item-btn btn text-white">
            <i class="iconly-Bag-2 icli"></i>
        </button>
    </div>
    
    <div class="item-section">
        <button class="close-button">
            <i class="fas fa-times"></i>
        </button>
        <h6>
            <i class="iconly-Bag-2 icli"></i>
            <span id="cart-count-dos">0</span>
            <br><br>
        </h6>
        <button onclick="location.href = '{{ route('cart.index') }}';" class="btn item-button btn-sm fw-bold" id="total-uno">$ 0.00</button>
    </div>
    <!-- Items section End -->

    <!-- Bg overlay Start -->
    <div class="bg-overlay"></div>
    <!-- Bg overlay End -->
    @include('partials.js')

    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        window.authUserId = {{ Auth::check() ? Auth::id() : 'null' }};
        window.syncCartWishlistUrl = "{{ route('sync.cart.wishlist') }}";
    </script>
    <script src="{{ asset('js/syncro.js') }}"></script>
</body>

</html>
