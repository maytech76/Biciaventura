
@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>Usuario Dashboard</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>

                            <li class="breadcrumb-item active">Usuario Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- User Dashboard Section Start -->
<section class="user-dashboard-section section-b-space">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-xxl-3 col-lg-4">
                <div class="dashboard-left-sidebar">
                    <div class="close-button d-flex d-lg-none">
                        <button class="close-sidebar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="profile-box">
                        <div class="cover-image">
                            <img src="{{ asset('assets/images/inner-page/cover-img.jpg') }}" class="img-fluid blur-up lazyload" alt="">
                        </div>

                        <div class="profile-contain">
                            <div class="profile-image">
                                <div class="position-relative">
                                    <img src="{{ asset('assets/images/inner-page/cover-img.jpg') }}" class="blur-up lazyload update_img" alt="">
                                </div>
                            </div>

                            <div class="profile-name">
                                <h3>{{ auth()->user()->name }}</h3>
                                <h6 class="text-content">{{ auth()->user()->email }}</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Menu de Opciones -->
                    <ul class="nav nav-pills user-nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#pills-dashboard" type="button"><i data-feather="home"></i>
                                Dashboard</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-order-tab" data-bs-toggle="pill" data-bs-target="#pills-order" type="button"><i data-feather="shopping-bag"></i>Mis Ordenes</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-wishlist-tab" data-bs-toggle="pill" data-bs-target="#pills-wishlist" type="button"><i data-feather="heart"></i>
                                Lista de Deseados</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-address-tab" data-bs-toggle="pill" data-bs-target="#pills-address" type="button" role="tab"><i data-feather="map-pin"></i>Direcciones</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab"><i data-feather="user"></i>
                                Perfil</button>
                        </li>
                    </ul>

                </div>
            </div>

            <div class="col-xxl-9 col-lg-8">
                <button class="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">
                    Mostrar Menu
                </button>
                <div class="dashboard-right-sidebar">
                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-dashboard" role="tabpanel">
                            <div class="dashboard-home">
                                <div class="title">
                                    <h2>My Dashboard</h2>
                                    <span class="title-leaf">
                                        <svg class="icon-width bg-gray">
                                            <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                        </svg>
                                    </span>
                                </div>

                                <div class="dashboard-user-name">
                                    <h6 class="text-content">Hola, <b class="text-title">{{ auth()->user()->name }}</b></h6>
                                    <p class="text-content">
                                        Desde tu panel de cuenta puedes ver el resumen de tu actividad reciente y actualizar tu información personal.
                                    </p>
                                </div>

                                <div class="total-box">
                                    <div class="row g-sm-4 g-3">
                                        <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                            <div class="total-contain">
                                                <img src="{{ asset('assets/images/svg/order.svg') }}" class="img-1 blur-up lazyload" alt="">
                                                <img src="{{ asset('assets/images/svg/order.svg') }}" class="blur-up lazyload" alt="">
                                                <div class="total-detail">
                                                    <h5>Total de Ordenes</h5>
                                                    <h3>{{ $totalOrdenes }}</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                            <div class="total-contain">
                                                <img src="{{ asset('assets/images/svg/pending.svg') }}" class="img-1 blur-up lazyload" alt="">
                                                <img src="{{ asset('assets/images/svg/pending.svg') }}" class="blur-up lazyload" alt="">
                                                <div class="total-detail">
                                                    <h5>Total Pendientes de Compra</h5>
                                                    <h3>{{ $totalPendientes }}</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                            <div class="total-contain">
                                                <img src="{{ asset('assets/images/svg/wishlist.svg') }}" class="img-1 blur-up lazyload" alt="">
                                                <img src="{{ asset('assets/images/svg/wishlist.svg') }}" class="blur-up lazyload" alt="">
                                                <div class="total-detail">
                                                    <h5>Total de Lista de Deseados</h5>
                                                    <h3>{{ $totalWishlist }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-wishlist" role="tabpanel">
                            <div class="dashboard-wishlist">
                                <div class="title">
                                    <h2>Mi Lista de Deseados</h2>
                                    <span class="title-leaf title-leaf-gray">
                                        <svg class="icon-width bg-gray">
                                            <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                        </svg>
                                    </span>
                                </div>
                                <div class="row">

                                    <div id="wishlist-container">
                                        <!-- Los productos se insertarán aquí dinámicamente desde LocalStorage -->
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-order" role="tabpanel">
                            <div class="dashboard-order">
                                <div class="title">
                                    <h2>Mis Ordenes</h2>
                                    <span class="title-leaf title-leaf-gray">
                                        <svg class="icon-width bg-gray">
                                            <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                        </svg>
                                    </span>
                                </div>

                                <div class="order-contain">

                                    @forelse($orders as $order)
                                        <div class="order-box dashboard-bg-box col-12">
                                            <div class="order-container">
                                                <div class="order-icon"><i data-feather="box"></i></div>
                                                <div class="order-detail">
                                                    <h4>Orden #{{ $order->id }}
                                                        <span class="{{ $order->status == 'paid' ? 'success-bg' : '' }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </h4>
                                                    <h6 class="text-content">Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</h6>
                                                    <h6 class="text-content">Dirección: {{ $order->shippingAddress->address ?? 'N/A' }}</h6>
                                                    <h6 class="text-content">Pago: {{ $order->payment->payment_method ?? 'N/A' }} - ${{ number_format($order->payment->amount ?? 0, 2) }}</h6>
                                                    <h6 class="text-content">Envío: $6.90</h6>
                                                </div>
                                            </div>

                                            @foreach($order->orderItems as $item)
                                                @if($item->product)
                                                    <div class="product-order-detail">
                                                        <a href="#" class="order-image">
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}"
                                                                class="blur-up lazyload"
                                                                style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #eee;">
                                                        </a>
                                                        <div class="order-wrap">
                                                            <h3>{{ $item->product->name }}</h3>
                                                            <p class="text-content">Precio Unitario: ${{ number_format($item->price, 2) }}</p>
                                                            <ul class="product-size">
                                                                <li><div class="size-box"><h6 class="text-content">Cantidad:</h6><h5>{{ $item->quantity }}</h5></div></li>
                                                                <li><div class="size-box"><h6 class="text-content">Subtotal:</h6><h5>${{ number_format($item->subtotal, 2) }}</h5></div></li>
                                                            </ul>
                                                        </div>

                                                        @php
                                                            $userReview = $item->product->reviews->where('user_id', auth()->id())->first();
                                                        @endphp

                                                        @if(!$userReview)
                                                            <button class="btn btn-md btn-theme-outline fw-bold"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#writereview"
                                                                onclick="setReviewData({{ $item->product->id }}, '', '')">
                                                                Escribir Reseña
                                                            </button>
                                                        @else
                                                            <button class="btn btn-md btn-theme-outline fw-bold"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#writereview"
                                                                onclick="setReviewData({{ $item->product->id }}, '{{ $userReview->rating }}', '{{ $userReview->comment }}', {{ $userReview->id }})">
                                                                Editar Reseña
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @empty
                                        <p class="text-danger">No tienes órdenes registradas.</p>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-address" role="tabpanel">
                            <div class="dashboard-address">
                                <div class="title title-flex">
                                    <div>
                                        <h2>Mis Direcciones</h2>
                                        <span class="title-leaf">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <button class="btn theme-bg-color text-white btn-sm fw-bold mt-lg-0 mt-3" data-bs-toggle="modal" data-bs-target="#add-address">
                                        <i data-feather="plus" class="me-2"></i> Nueva Dirección
                                    </button>
                                </div>

                                <div class="row g-sm-4 g-3">
                                    @foreach(auth()->user()->shippingAddresses as $address)
                                    <div class="col-xxl-4 col-xl-6 col-lg-12 col-md-6">
                                        <div class="address-box">
                                            <div class="table-responsive address-table">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Dirección:</td>
                                                            <td><p>{{ $address->address }}, {{ $address->city }}, {{ $address->state }}</p></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Código Postal:</td>
                                                            <td>{{ $address->zip_code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>País:</td>
                                                            <td>{{ $address->country }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="button-group">
                                                <!-- Botón editar -->
                                                <button class="btn btn-sm add-button w-100" data-bs-toggle="modal" data-bs-target="#edit-address-{{ $address->id }}">
                                                    <i data-feather="edit"></i> Editar
                                                </button>

                                                <!-- Botón eliminar -->
                                                <form action="{{ route('address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta dirección?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm add-button w-100" type="submit">
                                                        <i data-feather="trash-2"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="edit-address-{{ $address->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('address.update', $address->id) }}" method="POST" class="modal-content">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar Dirección</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-2">
                                                        <label>Dirección:</label>
                                                        <textarea name="address" class="form-control" required>{{ $address->address }}</textarea>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Ciudad:</label>
                                                        <input type="text" name="city" value="{{ $address->city }}" class="form-control" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Estado:</label>
                                                        <input type="text" name="state" value="{{ $address->state }}" class="form-control" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Código Postal:</label>
                                                        <input type="text" name="zip_code" value="{{ $address->zip_code }}" class="form-control" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>País:</label>
                                                        <input type="text" name="country" value="{{ $address->country }}" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn theme-bg-color text-white">Actualizar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-profile" role="tabpanel">
                            <div class="dashboard-profile">
                                <div class="title">
                                    <h2>Mi Perfil</h2>
                                    <span class="title-leaf">
                                        <svg class="icon-width bg-gray">
                                            <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                        </svg>
                                    </span>
                                </div>

                                <div class="profile-detail dashboard-bg-box">
                                    <div class="dashboard-title">
                                        <h3>{{ auth()->user()->name }}</h3>
                                        <div class="profile-name-detail">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfileModal">Editar</a>
                                        </div>
                                    </div>
                                    <div class="location-profile">
                                        <ul>
                                            <li>
                                                <div class="location-box">
                                                    <i data-feather="map-pin"></i>
                                                    <h6>{{ auth()->user()->address }}</h6>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="location-box">
                                                    <i data-feather="mail"></i>
                                                    <h6>{{ auth()->user()->email }}</h6>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="location-box">
                                                    <i data-feather="phone"></i>
                                                    <h6>{{ auth()->user()->phone }}</h6>
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
        </div>
    </div>
</section>
<!-- User Dashboard Section End -->

<!-- Review Modal Start -->
<div class="modal fade theme-modal question-modal" id="writereview" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Escribir una reseña</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body pt-0">
                <form id="reviewForm" action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" id="modal_product_id">
                    <input type="hidden" name="review_id" id="review_id">

                    <div class="review-box">
                        <label for="review_rating">Calificación</label>
                        <div class="product-rating">
                            <select name="rating" id="review_rating" class="form-control">
                                <option value="5">⭐⭐⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="1">⭐</option>
                            </select>
                        </div>
                    </div>

                    <div class="review-box">
                        <label for="review_comment" class="form-label">Tu reseña *</label>
                        <textarea id="review_comment" name="comment" rows="3" class="form-control" placeholder="Escribe tu reseña aquí"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-theme-outline fw-bold" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-md fw-bold text-light theme-bg-color">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Review Modal End -->

<!-- Modal Nueva Dirección -->
<div class="modal fade" id="add-address" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('address.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nueva Dirección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Dirección:</label>
                    <textarea name="address" class="form-control" required></textarea>
                </div>
                <div class="mb-2">
                    <label>Ciudad:</label>
                    <input type="text" name="city" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Estado:</label>
                    <input type="text" name="state" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Código Postal:</label>
                    <input type="text" name="zip_code" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>País:</label>
                    <input type="text" name="country" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn theme-bg-color text-white">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar usuario -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nombre completo</label>
                    <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required>
                </div>
                <div class="mb-3">
                    <label>Email (no editable)</label>
                    <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                </div>
                <div class="mb-3">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" name="phone" value="{{ auth()->user()->phone }}">
                </div>
                <div class="mb-3">
                    <label>Dirección</label>
                    <textarea name="address" rows="2" class="form-control">{{ auth()->user()->address }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Nueva Contraseña (opcional)</label>
                    <input type="password" class="form-control" name="password" placeholder="Dejar en blanco para mantener la actual">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme-outline" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-theme-outline">Actualizar</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script>
    function setReviewData(productId, rating = '', comment = '', reviewId = '') {
        document.querySelector('input[name="product_id"]').value = productId;
        document.querySelector('#review_rating').value = rating;
        document.querySelector('#review_comment').value = comment;
        document.querySelector('#review_id').value = reviewId;
    }
</script>
@endsection
