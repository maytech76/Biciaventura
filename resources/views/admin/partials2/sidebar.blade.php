<div class="sidebar-wrapper">
    <div id="sidebarEffect"><canvas width="723" height="711"></canvas></div>
    <div>
        <div class="logo-wrapper logo-wrapper-center">
            <a href="index.html" data-bs-original-title="" title="">
                <img class="img-fluid for-white" src="assets/images/logo/full-white.png" alt="logo">
            </a>
            <div class="back-btn">
                <i class="fa fa-angle-left"></i>
            </div>
            <div class="toggle-sidebar">
                <i class="ri-apps-line status_toggle middle sidebar-toggle"></i>
            </div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="index.html" data-bs-original-title="" title="">
                <img class="img-fluid main-logo main-white" src="assets/images/logo/logo.png" alt="logo">
                <img class="img-fluid main-logo main-dark" src="assets/images/logo/logo-white.png" alt="logo">
            </a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow disabled" id="left-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            </div>

            {{-- Sidebar-Menu --}}
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar" data-simplebar="init" style="display: block;"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">
                    <li class="back-btn"></li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav active" href="/" data-bs-original-title="" title="">
                            <i class="ri-home-line"></i>
                            <span>Dashboard</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                    </li>

                     {{-- Categorias --}}
                     <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-list-check-2"></i>
                            <span>Categorias</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{ route('categories.index') }}" data-bs-original-title="" title="">Listado de Categorias</a>
                            </li>

                            <li>
                                <a href="{{ route('categories.create') }}" data-bs-original-title="" title="">Nueva Categoria</a>
                            </li>
                        </ul>
                     </li>

                     {{-- Monedas --}}
                     <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-list-check-2"></i>
                            <span>Monedas</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{ route('dolar.index') }}" data-bs-original-title="" title="">Listado de Monedas</a>
                            </li>

                            <li>
                                <a href="{{-- {{ route('dolar.edit') }} --}}" data-bs-original-title="" title="">Nueva Tasa</a>
                            </li>
                        </ul>
                     </li>

                    {{-- Productos --}}
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-store-3-line"></i>
                            <span>Productos</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{ route('products.index') }}" data-bs-original-title="" title=""> Listado de Productos</a>
                            </li>

                            <li>
                                <a href="{{ route('products.create') }}" data-bs-original-title="" title="">Nuevo Producto</a>
                            </li>
                        </ul>
                    </li>


                    {{-- Atributos de Productos --}}
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-list-settings-line"></i>
                            <span>Atributos</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="attributes.html" data-bs-original-title="" title="">Atributos</a>
                            </li>

                            <li>
                                <a href="add-new-attributes.html" data-bs-original-title="" title="">Nuevo Atributo</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Ciudades-Zonas --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-user-3-line"></i>
                            <span>Ciudades-Zonas</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{route('cities.index')}} " data-bs-original-title="" title="">Listado de Ciudades</a>
                            </li>
                            <li>
                                <a href="all-users.html" data-bs-original-title="" title="">Listado de Zonas</a>
                            </li>
                            <li>
                                <a href="add-new-user.html" data-bs-original-title="" title="">Nueva Ciudad</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Eventos deportivos --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-user-3-line"></i>
                            <span>Eventos</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{route('events.index')}} " data-bs-original-title="" title="">Eventos Sports</a>
                            </li>
                            <li>
                                <a href="{{route('event_categories.index')}}" data-bs-original-title="" title="">Categorias Sports</a>
                            </li>
                            <li>
                                <a href="{{route('athletes.index')}}" data-bs-original-title="" title="">Atletas</a>
                            </li>
                            <li>
                                <a href="{{route('registrations.index')}}"data-bs-original-title="" title="">Inscripciones</a>
                            </li>
                            <li>
                                <a href="add-new-user.html" data-bs-original-title="" title="">Resultados por Evento</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Modulo de Taller--}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-settings-line"></i>
                            <span>Taller</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{route('customers.index')}} " data-bs-original-title="" title="">Clientes</a>
                            </li>
                            <li>
                                <a href="{{route('vehicles.index')}} " data-bs-original-title="" title="">Bicicletas</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Servicios</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Repuestos</a>
                            </li>
                            <li>
                                <a href="{{route('order-services.index')}} " data-bs-original-title="" title="">Orden de Servicio</a>
                            </li>
                            <li>
                                <a href="{{route('mechanics.index')}} " data-bs-original-title="" title="">Mécanico</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Comisiones</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Usuarios --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-user-3-line"></i>
                            <span>Usuarios</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{route('users.index')}}" data-bs-original-title="" title="">Listado de Usuarios</a>
                            </li>
                            <li>
                                <a href="{{route('users.create')}} " data-bs-original-title="" title="">Nuevo Usuario</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Roles de Usuario --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-user-3-line"></i>
                            <span>Roles y Permisos</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="#" data-bs-original-title="" title="">Usuarios</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Roles</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Permisos</a>
                            </li>
                        </ul>
                    </li>

                   

                    {{-- Cuentas x Cobrar --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-archive-line"></i>
                            <span>Cuentas x Cobrar</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{route('receivables.index')}}" data-bs-original-title="" title="">Listado de Cuentas</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Detalles de CxC</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Seguimiento de CxC</a>
                            </li>
                        </ul>
                    </li>


                    {{-- Ordenes de Compras --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-archive-line"></i>
                            <span>Ordenes</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{route('orders.index')}} " data-bs-original-title="" title="">Listado de Ordenes</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Detalles de Ordenes</a>
                            </li>
                            <li>
                                <a href="#" data-bs-original-title="" title="">Seguimiento de Ordenes</a>
                            </li>
                        </ul>
                    </li>

                        

                    {{-- Configuracion --}}
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)" data-bs-original-title="" title="">
                            <i class="ri-settings-line"></i>
                            <span>Configuraciones</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                        <ul class="sidebar-submenu" style="display: none;">
                            <li>
                                <a href="{{route('companies.index')}}" data-bs-original-title="" title="">Datos de la Empresa</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Reportes --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="reports.html" data-bs-original-title="" title="">
                            <i class="ri-file-chart-line"></i>
                            <span>Reportes</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                    </li>

                   {{--  <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="list-page.html" data-bs-original-title="" title="">
                            <i class="ri-list-check"></i>
                            <span>List Page</span>
                        <div class="according-menu"><i class="ri-arrow-right-s-line"></i></div></a>
                    </li> --}}
                </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 848px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 446px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></ul>
            </div>

            <div class="right-arrow" id="right-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </div>
        </nav>
    </div>
</div>