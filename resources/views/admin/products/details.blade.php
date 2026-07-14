@extends('layouts.app')

@section('title','Producto')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>{{ $product->name }}</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>

                            <li class="breadcrumb-item active">{{ $product->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="product-section">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-xxl-9 col-xl-8 col-lg-7 wow fadeInUp">
                <div class="row g-4">
                    <!-- Imagen del Producto -->
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="product-left-box">
                            <div class="row g-sm-4 g-2">
                                <div class="col-12">
                                    <div class="product-main no-arrow">
                                        <div>
                                            <div class="slider-image">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                     class="img-fluid image_zoom_cls-0 blur-up lazyload"
                                                     alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Producto -->
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="right-box-contain">
                            <h2 class="name">{{ $product->name }}</h2>

                            <div class="price-rating">
                                <h3 class="theme-color price">
                                    ${{ number_format($product->price, 2) }}
                                    <del class="text-content">${{ number_format($product->price2, 2) }}</del>
                                </h3>

                                <!-- Calificación del Producto -->
                                <div class="product-rating custom-rate">
                                    <ul class="rating">
                                        @php
                                            $rating = round($product->reviews()->avg('rating'));
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li>
                                                <i data-feather="star" class="{{ $i <= $rating ? 'fill' : '' }}"></i>
                                            </li>
                                        @endfor
                                    </ul>
                                    <span class="review">{{ $product->reviews()->count() }} Reseñas</span>
                                </div>
                            </div>

                            <div class="product-contain">
                                <p class="w-100">{{ $product->description }}</p>
                            </div>

                            <!-- Selección de cantidad y botones de acción -->
                            <div class="note-box product-package">
                                <div class="cart_qty qty-box product-qty">
                                    <div class="input-group">
                                        <button type="button" class="qty-left-minus" onclick="updateQuantity({{ $product->id }}, 'minus')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <input class="form-control input-number qty-input" type="text" id="qty-{{ $product->id }}" value="1">
                                        <button type="button" class="qty-right-plus" onclick="updateQuantity({{ $product->id }}, 'plus')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <button class="btn btn-md bg-dark cart-button text-white w-100"
                                        onclick="addToCart({{ $product->id }}, '{{ asset('storage/' . $product->image) }}', '{{ route('product.details', [$product->id, $product->slug]) }}', '{{ $product->price }}' , '{{ $product->name }}')">
                                    Agregar al Carrito
                                </button>
                            </div>

                            <div class="buy-box">
                                <a href="javascript:void(0)" onclick="addToWishlist({{ $product->id }}, '{{ asset('storage/' . $product->image) }}', '{{ route('product.details', [$product->id, $product->slug]) }}', '{{ $product->price }}' , '{{ $product->name }}')">
                                    <i data-feather="heart"></i>
                                    <span>Agregar a Wishlist</span>
                                </a>
                            </div>

                            <!-- Opciones de Pago -->
                            <div class="payment-option">
                                <div class="product-title">
                                    <h4>Pago 100% Seguro</h4>
                                </div>
                                <ul>
                                    <li><a href="#"><img src="../assets/images/product/payment/1.svg" class="blur-up lazyload" alt=""></a></li>
                                    <li><a href="#"><img src="../assets/images/product/payment/2.svg" class="blur-up lazyload" alt=""></a></li>
                                    <li><a href="#"><img src="../assets/images/product/payment/3.svg" class="blur-up lazyload" alt=""></a></li>
                                    <li><a href="#"><img src="../assets/images/product/payment/4.svg" class="blur-up lazyload" alt=""></a></li>
                                    <li><a href="#"><img src="../assets/images/product/payment/5.svg" class="blur-up lazyload" alt=""></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de la Marca -->
            <div class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block wow fadeInUp">
                <div class="right-sidebar-box">
                    <div class="vendor-box">
                        <div class="vendor-contain">
                            <div class="vendor-name">
                                <h5 class="fw-500">{{ $product->brand->name }}</h5>
                            </div>
                        </div>

                        <p class="vendor-detail">Explora productos de alta calidad de {{ $product->brand->name }}.
                        Disfruta de la mejor selección en Andercode eCommerce.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="product-section-box m-0">
                    <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Descripción</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button" role="tab">Reseñas</button>
                        </li>
                    </ul>

                    <div class="tab-content custom-tab" id="myTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <div class="product-description">
                                <div class="nav-desh">
                                    <p>{{ $product->description }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="review" role="tabpanel">
                            <div class="review-box">
                                <div class="row">
                                    <!-- Sección de Rating Promedio -->
                                    <div class="col-xl-5">
                                        <div class="product-rating-box">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="product-main-rating">
                                                        <h2>{{ number_format($product->reviews->avg('rating'), 2) }}
                                                            <i data-feather="star"></i>
                                                        </h2>
                                                        <h5>Calificación General</h5>
                                                    </div>
                                                </div>

                                                <div class="col-xl-12">
                                                    <ul class="product-rating-list">
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            @php
                                                                $count = $product->reviews->where('rating', $i)->count();
                                                                $percentage = $product->reviews->count() > 0 ? ($count / $product->reviews->count()) * 100 : 0;
                                                            @endphp
                                                            <li>
                                                                <div class="rating-product">
                                                                    <h5>{{ $i }}<i data-feather="star"></i></h5>
                                                                    <div class="progress">
                                                                        <div class="progress-bar" style="width: {{ $percentage }}%;">
                                                                        </div>
                                                                    </div>
                                                                    <h5 class="total">{{ $count }}</h5>
                                                                </div>
                                                            </li>
                                                        @endfor
                                                    </ul>

                                                    <!-- Botón para dejar una reseña -->
                                                    @auth
                                                        @if(auth()->user()->hasPurchasedProduct($product->id))
                                                            <div class="review-title-2">
                                                                <h4 class="fw-bold">Califica este producto</h4>
                                                                <p>Comparte tu experiencia con otros clientes</p>

                                                                @php
                                                                    $userReview = $product->reviews->where('user_id', auth()->id())->first();
                                                                @endphp

                                                                @if($userReview)
                                                                    <!-- Editar reseña existente -->
                                                                    <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#writereview"
                                                                        onclick="editReview({{ $userReview->id }}, '{{ $userReview->rating }}', '{{ $userReview->comment }}')">
                                                                        Editar mi reseña
                                                                    </button>
                                                                @else
                                                                    <!-- Agregar nueva reseña -->
                                                                    <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#writereview">
                                                                        Escribir una reseña
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lista de Reseñas -->
                                    <div class="col-xl-7">
                                        <div class="review-people">
                                            <ul class="review-list">
                                                @forelse($product->reviews as $review)
                                                    <li>
                                                        <div class="people-box">
                                                            <div class="people-comment">
                                                                <div class="people-name">
                                                                    <a href="javascript:void(0)" class="name">{{ $review->user->name }}</a>
                                                                    <div class="date-time">
                                                                        <h6 class="text-content">{{ $review->created_at->format('d M Y h:i A') }}</h6>
                                                                        <div class="product-rating">
                                                                            <ul class="rating">
                                                                                @for ($i = 1; $i <= 5; $i++)
                                                                                    <li>
                                                                                        <i data-feather="star" class="{{ $i <= $review->rating ? 'fill' : '' }}"></i>
                                                                                    </li>
                                                                                @endfor
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="reply">
                                                                    <p>{{ $review->comment }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @empty
                                                    <p class="text-center">No hay reseñas para este producto.</p>
                                                @endforelse
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
    </div>
</section>

<section class="related-products section-b-space">
    <div class="container-fluid-lg">
        <h2 class="text-center mb-4">🔥 Productos Más Vendidos 🔥</h2>

        <div class="row">
            @foreach ($topSellingProducts->chunk(6) as $chunk)
                <div class="row">
                    @foreach ($chunk as $topProduct)
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                            <div class="product-box-4 wow fadeInUp">
                                <div class="product-image">
                                    <div class="label-flex">
                                        <button class="btn p-0 wishlist btn-wishlist notifi-wishlist"
                                            onclick="addToWishlist({{ $topProduct->id }}, '{{ asset('storage/' . $topProduct->image) }}', '{{ route('product.details', [$topProduct->id, $topProduct->slug]) }}', '{{ $topProduct->price }}' , '{{ $topProduct->name }}')">
                                            <i class="iconly-Heart icli"></i>
                                        </button>
                                    </div>

                                    <a href="{{ route('product.details', [$topProduct->id, $topProduct->slug]) }}">
                                        <img src="{{ asset('storage/' . $topProduct->image) }}" class="img-fluid" alt="{{ $topProduct->name }}">
                                    </a>

                                    <ul class="option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Vista Rápida">
                                            <a href="{{ route('product.details', [$topProduct->id, $topProduct->slug]) }}">
                                                <i class="iconly-Show icli"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="product-detail">
                                    <ul class="rating">
                                        @php $rating = round($topProduct->reviews()->avg('rating')) @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li>
                                                <i data-feather="star" class="{{ $i <= $rating ? 'fill' : '' }}"></i>
                                            </li>
                                        @endfor
                                    </ul>
                                    <a href="{{ route('product.details', [$topProduct->id, $topProduct->slug]) }}">
                                        <h5 class="name">{{ $topProduct->name }}</h5>
                                    </a>
                                    <h5 class="price theme-color">
                                        ${{ number_format($topProduct->price, 2) }}
                                        <del>${{ number_format($topProduct->price2, 2) }}</del>
                                    </h5>
                                    <div class="price-qty">
                                        <div class="counter-number">
                                            <div class="counter">
                                                <div class="qty-left-minus" onclick="updateQuantity({{ $topProduct->id }}, 'minus')">
                                                    <i class="fa-solid fa-minus"></i>
                                                </div>
                                                <input class="form-control input-number qty-input" type="text" id="qty-{{ $topProduct->id }}" value="1">
                                                <div class="qty-right-plus" onclick="updateQuantity({{ $topProduct->id }}, 'plus')">
                                                    <i class="fa-solid fa-plus"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="buy-button buy-button-2 btn btn-cart"
                                            onclick="addToCart({{ $topProduct->id }}, '{{ asset('storage/' . $topProduct->image) }}', '{{ route('product.details', [$topProduct->id, $topProduct->slug]) }}', '{{ $topProduct->price }}' , '{{ $topProduct->name }}')">
                                            <i class="iconly-Buy icli text-white m-0"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>


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
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" id="review_id" name="review_id" value="">

                    <div class="review-box">
                        <label>Calificación</label>
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
@endsection
