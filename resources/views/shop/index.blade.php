@extends('layouts.app')

@section('title','Shop')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>Listado de Productos</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>

                            <li class="breadcrumb-item active">Listado de Productos</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop Section Start -->
<section class="section-b-space shop-section">
    <div class="container-fluid-lg">
        <div class="row">
            <!-- Sidebar de filtros -->
            <div class="col-custom-3 wow fadeInUp">
                <div class="left-box">
                    <div class="shop-left-sidebar">
                        <div class="accordion custom-accordion" id="accordionExample">

                            <!-- Filtro por Categorías -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                        <span>Categorías</span>
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <ul class="category-list custom-padding">
                                            @foreach ($categories as $cat)
                                                <li>
                                                    <div class="form-check ps-0 m-0 category-list-box">
                                                        <input class="checkbox_animated" type="checkbox" name="categories[]" value="{{ $cat->id }}"
                                                            {{ in_array($cat->id, $categoriesFilter) ? 'checked' : '' }}
                                                            onchange="applyFilters()">
                                                        <label class="form-check-label">
                                                            <span class="name">{{ $cat->name }}</span>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtro por Marcas -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                        <span>Marcas</span>
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <ul class="category-list custom-padding">
                                            @foreach ($brands as $brand)
                                                <li>
                                                    <div class="form-check ps-0 m-0 category-list-box">
                                                        <input class="checkbox_animated" type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                                            {{ in_array($brand->id, $brandsFilter) ? 'checked' : '' }}
                                                            onchange="applyFilters()">
                                                        <label class="form-check-label">
                                                            <span class="name">{{ $brand->name }}</span>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtro por Precio -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                        <span>Precio</span>
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <ul class="category-list custom-padding">
                                            @php
                                                $priceRanges = [
                                                    '5-100' => '$ 5 - $ 100',
                                                    '101-200' => '$ 101 - $ 200',
                                                    '201-300' => '$ 201 - $ 300',
                                                    '301-400' => '$ 301 - $ 400',
                                                    '401-500' => '$ 401 - $ 500',
                                                ];
                                            @endphp
                                            @foreach ($priceRanges as $range => $label)
                                                <li>
                                                    <div class="form-check ps-0 m-0 category-list-box">
                                                        <input class="checkbox_animated" type="checkbox" name="prices[]" value="{{ $range }}"
                                                            {{ in_array($range, $pricesFilter) ? 'checked' : '' }}
                                                            onchange="applyFilters()">
                                                        <label class="form-check-label">
                                                            <span class="name">{{ $label }}</span>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtro por Rating -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                                        <span>Rating</span>
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <ul class="category-list custom-padding">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <li>
                                                    <div class="form-check ps-0 m-0 category-list-box">
                                                        <input class="checkbox_animated" type="checkbox" name="ratings[]" value="{{ $i }}"
                                                            {{ in_array($i, $ratingsFilter) ? 'checked' : '' }}
                                                            onchange="applyFilters()">
                                                        <label class="form-check-label">
                                                            <ul class="rating">
                                                                @for ($j = 1; $j <= 5; $j++)
                                                                    <li>
                                                                        <i data-feather="star" class="{{ $j <= $i ? 'fill' : '' }}"></i>
                                                                    </li>
                                                                @endfor
                                                            </ul>
                                                            <span class="text-content">({{ $i }} Estrellas)</span>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de productos -->
            <div class="col-custom- wow fadeInUp">
                <div class="show-button">
                    <div class="filter-button-group mt-0">
                        <div class="filter-button d-inline-block d-lg-none">
                            <a><i class="fa-solid fa-filter"></i> Filtrar</a>
                        </div>
                    </div>
                </div>

                <div class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section">
                    @foreach ($products as $product)
                    <div>
                        <div class="product-box-3 h-100 wow fadeInUp">
                            <div class="product-box-4 wow fadeInUp">
                                <div class="product-image">
                                    <div class="label-flex">
                                        <button class="btn p-0 wishlist btn-wishlist notifi-wishlist"
                                            onclick="addToWishlist({{ $product->id }}, '{{ asset('storage/' . $product->main_image) }}', '{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}', '{{ $product->price }}' , '{{ $product->name }}')">
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
                                    <span class="span-name">{{ $product->category->name }}</span>
                                    <ul class="rating">
                                        @php $rating = round($product->reviews()->avg('rating')) @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li>
                                                <i data-feather="star" class="{{ $i <= $rating ? 'fill' : '' }}"></i>
                                            </li>
                                        @endfor
                                    </ul>
                                    <span class="span-name">{{ $product->brand->name }}</span>
                                    <a href="{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}">
                                        <h5><strong>{{ $product->name }}</strong></h5>
                                    </a>
                                    <p class="text-justify">
                                        {{ Str::limit($product->description, 67, '...') }}
                                    </p>
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
                                            onclick="addToCart({{ $product->id }}, '{{ asset('storage/' . $product->main_image) }}', '{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}', '{{ $product->price }}', '{{ $product->name }}')">
                                            <i class="iconly-Buy icli text-white m-0"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación personalizada -->
                <nav class="custom-pagination">
                    <ul class="pagination justify-content-center">
                        @php
                            // Obtener los parámetros actuales de la URL
                            $queryParams = request()->except('page');
                        @endphp

                        <!-- Botón para ir a la primera página -->
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->appends($queryParams)->url(1) }}" tabindex="-1">
                                <i class="fa-solid fa-angles-left"></i>
                            </a>
                        </li>

                        <!-- Botón para retroceder 1 página -->
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->appends($queryParams)->previousPageUrl() ?? 'javascript:void(0)' }}">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        </li>

                        <!-- Mostrar solo 5 páginas alrededor de la actual -->
                        @php
                            $start = max(1, $products->currentPage() - 2);
                            $end = min($products->lastPage(), $products->currentPage() + 2);
                        @endphp

                        @for ($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $products->appends($queryParams)->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <!-- Botón para avanzar 1 página -->
                        <li class="page-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->appends($queryParams)->nextPageUrl() ?? 'javascript:void(0)' }}">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </li>

                        <!-- Botón para ir a la última página -->
                        <li class="page-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->appends($queryParams)->url($products->lastPage()) }}">
                                <i class="fa-solid fa-angles-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</section>

<script>
    function applyFilters() {
        let url = new URL(window.location.href);

        // Obtener todas las categorías seleccionadas
        let categories = [];
        document.querySelectorAll('input[name="categories[]"]:checked').forEach((checkbox) => {
            categories.push(checkbox.value);
        });

        // Obtener todas las marcas seleccionadas
        let brands = [];
        document.querySelectorAll('input[name="brands[]"]:checked').forEach((checkbox) => {
            brands.push(checkbox.value);
        });

        // Obtener todos los precios seleccionados
        let prices = [];
        document.querySelectorAll('input[name="prices[]"]:checked').forEach((checkbox) => {
            prices.push(checkbox.value);
        });

        // Obtener todos los ratings seleccionados
        let ratings = [];
        document.querySelectorAll('input[name="ratings[]"]:checked').forEach((checkbox) => {
            ratings.push(checkbox.value);
        });

        // Si hay categorías seleccionadas, actualizar la URL correctamente
        if (categories.length > 0) {
            url.searchParams.set('categories', categories.join(',')); // Convertir array a string separado por comas
        } else {
            url.searchParams.delete('categories'); // Si no hay selección, eliminar el parámetro
        }

        if (brands.length > 0) {
            url.searchParams.set('brands', brands.join(','));
        } else {
            url.searchParams.delete('brands');
        }

        if (prices.length > 0) {
            url.searchParams.set('prices', prices.join(','));
        } else {
            url.searchParams.delete('prices');
        }

        if (ratings.length > 0) {
            url.searchParams.set('ratings', ratings.join(','));
        } else {
            url.searchParams.delete('ratings');
        }

        // Redirigir a la URL con los filtros aplicados
        window.location.href = url.toString();
    }
</script>
@endsection

