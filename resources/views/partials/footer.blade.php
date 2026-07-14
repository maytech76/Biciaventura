<footer class="section-t-space footer-section-2" style="background-color: rgb(41, 40, 40);">
    <div class="container-fluid-lg">

        <div class="service-section">
            <div class="row g-3">
                
                <div class="col-12">
                    <div class="service-contain">
                        <!-- Producto siempre fresco -->
                        <div class="service-box">
                            <div class="service-image">
                                {{-- <img src="../assets/svg/product.svg" class="blur-up lazyload" alt="Productos frescos"> --}}
                            </div>

                            <div class="service-detail">
                                {{-- <h5>Productos Siempre Frescos</h5> --}}
                            </div>
                        </div>

                        <!-- Envío gratuito -->
                        <div class="service-box">
                            <div class="service-image">
                               {{--  <img src="../assets/svg/delivery.svg" class="blur-up lazyload" alt="Envío gratis"> --}}
                            </div>

                            <div class="service-detail">
                               {{--  <h5>Envío Gratis en Compras Mayores a $50</h5> --}}
                            </div>
                        </div>

                        <!-- Descuentos diarios -->
                        <div class="service-box">
                            <div class="service-image">
                                {{-- <img src="../assets/svg/discount.svg" class="blur-up lazyload" alt="Descuentos diarios"> --}}
                                <h5 class="textorange">Descubre:</h5>
                            </div>

                            <div class="service-detail">
                                <h5 style="color: rgb(255, 149, 0)">Descuentos Especiales Todos los Días</h5>
                            </div>
                        </div>

                        <!-- Mejor precio del mercado -->
                        <div class="service-box">
                            <div class="service-image">
                                {{-- <img src="../assets/svg/market.svg" class="blur-up lazyload" alt="Mejor precio"> --}}
                                <h5 class="textorange">Aqui encuentras:</h5>
                            </div>

                            <div class="service-detail">
                                <h5 style="color: rgb(255, 149, 0)">El Mejor Precio del Mercado</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .textorange {
                        color: rgb(255, 149, 0);
                    }
                </style>

            </div>
        </div>


        {{-- Links Redes Sociales --}}
        <div class="main-footer">
            <div class="row g-md-4 gy-sm-5 gy-2">
                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <a href="{{ route('home') }}" class="foot-logo">
                        <img src="{{ asset('assets/images/logo/maydev.png') }}" class="img-fluid" alt="" style="width: 75px;">
                    </a>
                    <p class="information-text text-white">te esperamos en todas nuestra redes sociales, informate de todas nuestras ofertas y eventos deportivos</p>
                    <ul class="social-icon">
                        <li>
                            <a href="www.facebook.com">
                                <i class="textorange fab fa-facebook-f"></i>
                            </a>
                        </li>
                        <li>
                            <a href="www.goolge.com">
                                <i class="textorange fab fa-google"></i>
                            </a>
                        </li>
                        <li>
                            <a href="www.twitter.com">
                                <i class="textorange fab fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="www.instagram.com">
                                <i class="textorange fab fa-instagram"></i>
                            </a>
                        </li>
                       {{--  <li>
                            <a href="www.pinterest.com">
                                <i class="fab fa-pinterest-p"></i>
                            </a>
                        </li> --}}
                    </ul>
                </div>

                <div class="col-xxl-2 col-xl-4 col-sm-6">

                </div>

                <div class="col-xxl-2 col-xl-4 col-sm-6">

                </div>

                <div class="col-xxl-2 col-xl-4 col-sm-6">

                </div>

                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div class="footer-title">
                        <h4 class="textorange">Información de Contacto</h4>
                    </div>
                    <ul class="footer-address footer-contact">
                        <li>
                            <a href="javascript:void(0)">
                                <div class="inform-box">
                                    <i data-feather="phone" class="textorange"></i>
                                    <p class="textorange">Telefono: 0274-01020304</p>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)">
                                <div class="inform-box">
                                    <i data-feather="mail" class="textorange"></i>
                                    <p class="textorange">Email: biciaventura@gmail.com</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sub-footer section-small-space">
            <div class="left-footer">
                <p style="color:rgb(255, 149, 0)">{{ date('Y') }} Copyright Biciaventura</p>
            </div>
            <div class="right-footer">
                <ul class="payment-box">
                    <li>
                        <img src="../assets/images/icon/paymant/visa.png" alt="">
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <style>
        .textorange {
            color: rgb(255, 149, 0);
        }
    </style>
</footer>
