@extends('layouts.app')

@section('title','Inicio')

@section('content')
<!-- home section start -->
@include('partials.home')
<!-- Home Section End -->

<!-- Value Section Start -->
@include('partials.value')
<!-- Value Section End -->

<!-- Banner Section Start -->
@include('partials.banner1')
<!-- Banner Section End -->

<!-- Banner Section Start -->
@include('partials.banner2')
<!-- Banner Section End -->

<!-- Product Section Start -->
<section class="product-section">
    <div class="container-fluid-lg">
        <div class="title">
            <h2>Top Productos</h2>
        </div>

        @foreach ($topSellingProducts->chunk(6) as $chunk)
            <div class="row">
                @foreach ($chunk as $product)
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                        <div class="product-box-4 wow fadeInUp">
                            <div class="product-image">
                                <div class="label-flex">
                                    <button class="btn p-0 wishlist btn-wishlist notifi-wishlist"
                                        onclick="addToWishlist({{ $product->id }}, '{{ asset('storage/' . $product->image) }}', '{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}', '{{ $product->price }}' , '{{ $product->name }}')">
                                        <i class="iconly-Heart icli"></i>
                                    </button>
                                </div>

                                <a href="{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}">
                                    <img src="{{ asset('storage/' . $product->main_image) }}" class="img-fluid" alt="{{ $product->name }}">
                                    
                                </a>

                                <ul class="option">
                                    <li data-bs-toggle="tooltip" data-bs-placement="top" title="Vista Rápida">
                                        <a href="{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}">
                                            <i class="iconly-Show icli"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="product-detail">
                                <ul class="rating">
                                    @php $rating = round($product->reviews()->avg('rating')) @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li>
                                            <i data-feather="star" class="{{ $i <= $rating ? 'fill' : '' }}"></i>
                                        </li>
                                    @endfor
                                </ul>
                                <a href="{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}">
                                    <h5 class="name">{{ $product->name }}</h5>
                                </a>
                                <h5 class="price theme-color">
                                    $ {{ number_format($product->price, 2) }}
                                    <del>$ {{ number_format($product->price2, 2) }}</del>
                                </h5>
                                <div class="price-qty">
                                    <div class="counter-number">
                                        <div class="counter">
                                            <div class="qty-left-minus" onclick="updateQuantity({{ $product->id }}, 'minus')">
                                                <i class="fa-solid fa-minus"></i>
                                            </div>
                                            <input class="form-control input-number qty-input" type="text" id="qty-{{ $product->id }}" value="1">
                                            <div class="qty-right-plus" onclick="updateQuantity({{ $product->id }}, 'plus')">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="buy-button buy-button-2 btn btn-cart"
                                        onclick="addToCart({{ $product->id }}, '{{ asset('storage/' . $product->image) }}', '{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}', '{{ $product->price }}', '{{ $product->name }}')">
                                        <i class="iconly-Buy icli text-white m-0"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div> <!-- Cierra el grupo de 6 productos -->
        @endforeach

    </div>
</section>
<!-- Product Section End -->

<!-- Newsletter Section Start -->
@include('partials.news')
<!-- Newsletter Section End -->
@endsection
