<section class="home-section-2 home-section-small section-b-space">
    <div class="container-fluid-lg">
        <div class="row g-4">
            <!-- Sección principal -->
            <div class="col-xxl-6 col-md-8">
                <div class="home-contain h-100">
                    <img src="{{ asset('assets/images/veg-3/home/1.png') }}" class="img-fluid bg-img blur-up lazyload" alt="Frutas y Verduras Orgánicas">
                    <div class="home-detail home-width p-center-left position-relative">
                        <div>
                            <h6 class="ls-expanded theme-color">ORGÁNICO</h6>
                            <h1 class="fw-bold w-100">100% Fresco</h1>
                            <h3 class="text-content fw-light">Frutas y Verduras</h3>
                            <p class="d-sm-block d-none">Envío gratuito en todos tus pedidos. Nosotros lo llevamos, tú disfrutas.</p>
                            <button onclick="location.href = '{{ route('shop.index') }}';" class="btn mt-sm-4 btn-2 theme-bg-color text-white mend-auto btn-2-animation">
                                Comprar Ahora
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de productos destacados -->
            <div class="col-xxl-3 col-md-4 ratio_medium d-md-block d-none">
                <div class="home-contain home-small h-100">
                    <div class="h-100">
                        <img src="{{ asset('assets/images/veg-3/home/2.png') }}" class="img-fluid bg-img blur-up lazyload" alt="Mercado de agricultores">
                    </div>
                    <div class="home-detail text-center p-top-center w-100 text-white">
                        <div>
                            <h4 class="fw-bold">Fresco y 100% Orgánico</h4>
                            <h5 class="text-center">Mercado de Agricultores</h5>
                            <button class="btn bg-white theme-color mt-3 home-button mx-auto btn-2" onclick="location.href = '{{ route('shop.index') }}';">
                                Comprar Ahora
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de ofertas y estilo de vida -->
            <div class="col-xxl-3 ratio_65 d-xxl-block d-none">
                <div class="row g-3">
                    <div class="col-xxl-12 col-sm-6">
                        <div class="home-contain">
                            <a href="{{ url('shop-left-sidebar') }}">
                                <img src="{{ asset('assets/images/veg-3/home/3.png') }}" class="img-fluid bg-img blur-up lazyload" alt="Estilo de Vida Orgánico">
                            </a>
                            <div class="home-detail text-white p-center text-center">
                                <div>
                                    <h4 class="text-center">Estilo de Vida Orgánico</h4>
                                    <h5 class="text-center">Las Mejores Ofertas del Fin de Semana</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-12 col-sm-6">
                        <div class="home-contain">
                            <a href="{{ url('shop-left-sidebar') }}">
                                <img src="{{ asset('assets/images/veg-3/home/4.png') }}" class="img-fluid bg-img blur-up lazyload" alt="Descuento Especial">
                            </a>
                            <div class="home-detail text-white w-50 p-center-left home-p-sm">
                                <div>
                                    <h4 class="fw-bold">Alimentos Seguros Salvan Vidas</h4>
                                    <h5>Oferta con Descuento</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
