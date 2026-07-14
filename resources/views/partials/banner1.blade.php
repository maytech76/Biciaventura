<section class="banner-section">
    <div class="container-fluid-lg">
        <div class="row gy-xl-0 gy-3">

            <!-- Banner 1 -->
            <div class="col-xl-6">
                <div class="banner-contain-3 hover-effect">
                    <img src="{{ asset('assets/images/veg-3/banner/1.png') }}" class="bg-img img-fluid" alt="Verduras frescas y alimentación diaria">
                    <div class="banner-detail banner-details-dark text-white p-center-left w-50 position-relative mend-auto">
                        <div>
                            <h6 class="ls-expanded text-uppercase">Premium</h6>
                            <h3 class="mb-sm-3 mb-1">Verduras Frescas y Alimentación Diaria</h3>
                            <h4>Obtén un 50% de descuento</h4>
                            <button class="btn theme-color bg-white btn-md fw-bold mt-sm-3 mt-1 mend-auto" onclick="location.href = '{{ route('shop.index') }}';">
                                Comprar Ahora
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner 2 -->
            <div class="col-xl-6">
                <div class="banner-contain-3 hover-effect">
                    <img src="{{ asset('assets/images/veg-3/banner/2.png') }}" class="bg-img img-fluid" alt="Frutas 100% naturales y saludables">
                    <div class="banner-detail text-dark p-center-left w-50 position-relative mend-auto">
                        <div>
                            <h6 class="ls-expanded text-uppercase">Disponible</h6>
                            <h3 class="mb-sm-3 mb-1">Frutas 100% Naturales y Saludables</h3>
                            <h4 class="text-content">Especial de Fin de Semana</h4>
                            <button class="btn theme-bg-color text-white btn-md fw-bold mt-sm-3 mt-1 mend-auto" onclick="location.href = '{{ route('shop.index') }}';">
                                Comprar Ahora
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
