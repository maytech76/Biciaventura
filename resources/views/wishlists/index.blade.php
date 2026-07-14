@extends('layouts.app')

@section('title','Lista de Deseos')

@section('content')
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>Lista de Deseados</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>

                            <li class="breadcrumb-item active">Lista de Deseados</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="wishlist-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-3 g-2">
                <div id="wishlist-container">
                    <!-- Los productos se insertarán aquí dinámicamente desde LocalStorage -->
                </div>
            </div>
        </div>
</section>
@endsection

