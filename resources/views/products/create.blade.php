@extends('admin.layouts.master')


@section('content')

@section('title', 'product')


<div class="page-body">

    <!-- New Product Add Start -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-12 m-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Registro de Productos</h5>
                                </div>


                                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="theme-form theme-form-2 mega-form">

                                    <div class="row">
                                        <div class="col-md-6">

                                            {{-- Nombre del Producto --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class=" col-sm-3 mb-0">Nombre</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control border border-secondary" type="text" id="name" name="name" placeholder="Agregar Nombre del producto">
                                                </div>
                                            </div>

                                            {{-- Categorias --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Category</label>
                                                <div class="col-sm-9">
                                                    <select class="js-example-basic-single w-100 form-select border border-secondary" name="state">

                                                        <option value="">Seleccione una Categoria</option>
                                                        @foreach ($categories as $category)

                                                         <option value="{{$category->id}} ">{{$category->name}} </option>
                                                        
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Marca del producto --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Marca</label>
                                                <div class="col-sm-9">
                                                    <select class="js-example-basic-single w-100 form-select border border-secondary">
                                                        
                                                        <option value="">Selecciona un Marca</option>
                                                         @foreach ($brands as $brand)

                                                           <option value="{{$brand->id}} ">{{$brand->name}}</option>
                                                             
                                                         @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Nombre de la Unidad ****--}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Unidad </label>
                                                <div class="col-sm-9">
                                                    <select name="unit" id="unit" class="form-select border border-secondary">
                                                        <option value="">Seleccione una unidad</option>
                                                            <option value="UND">UNIDA</option>
                                                            <option value="CAJA">CAJA</option>
                                                            <option value="PAR">PAR</option>
                                                            <option value="KIT">KIT</option>
                                                            <option value="SET">SET</option>
                                                            <option value="JUEGO">JUEGO</option>
                                                            <option value="PIEZA">PIEZA</option>
                                                            <option value="MTS">METRO</option>
                                                            <option value="RECARGA">RECARGA</option>
                                                            <option value="KG">KILOS</option>
                                                            <option value="LTS">LITROS</option>
                                                            
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Slug del producto --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Slug</label>
                                                <div class="col-sm-9">
                                                    <div class="bs-example">
                                                        <input type="text" class="form-control border border-secondary" placeholder="Type tag & hit enter" id="#inputTag" data-role="tagsinput">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Es intercambiable ***--}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label">Intercambiable</label>
                                                <div class="col-sm-9">
                                                    <label class="switch sm-switch">
                                                        <input type="checkbox" name="interchangeable" value="1" 
                                                            {{ old('interchangeable') ? 'checked' : '' }}>
                                                        <span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            {{-- Es reembolsable --}}
                                            <div class="row align-items-center">
                                                <label class="col-sm-3 col-form-label">Reembolso</label>
                                                <div class="col-sm-9">
                                                    <!-- Campo hidden con valor 0 por defecto -->
                                                    <input type="hidden" name="refundable" id="refundable" value="0">
                                                    <label class="switch sm-switch">
                                                        <input type="checkbox" checked><span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        
                                        </div>

                                        <div class="col-md-6" style="margin-top: -80px">

                                            {{-- descripcion --}}
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-header-2">
                                                        <h5>Descripción</h5>
                                                    </div>

                                                   
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control w-100 form-control border border-secondary" name="description" id="description" cols="40" rows="4"></textarea>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                </div>
                                            </div>

                                            {{-- Imagenes ***--}}
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-header-2">
                                                        <h5>Imagenes del Producto</h5>
                                                    </div>

                                                    
                                                        <div class="mb-4 row align-items-center">
                                                            <label class="col-sm-3 col-form-label">Imagenes</label>
                                                            <div class="col-sm-9">
                                                                <input class="form-control form-choose" type="file" id="main_image" name="main_image" multiple="">
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-center">
                                                            <label class="col-sm-3 col-form-label">Imagen Portada</label>
                                                            <div class="col-sm-9">
                                                                <input class="form-control form-choose" type="file" id="cover_image" name="cover_image">
                                                            </div>
                                                        </div>

                                                         {{-- Codigo de Barra --}}
                                                        <div class="mt-4 row align-items-center">
                                                            <label class="col-sm-3 col-form-label ">Codigo de Barra</label>
                                                            <div class="col-sm-9">
                                                                <div class="bs-example">
                                                                    <input type="text" class="form-control border border-secondary" placeholder="Ingrese Codigo de Barra" id="codebar" name="codebar" data-role="tagsinput">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    
                                    <div class="row">

                                        <div class="col-md-6">
                                             {{-- Link de Video ***--}}
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-header-2">
                                                        <h5>Video de Producto</h5>
                                                    </div>

                                                    <form class="theme-form theme-form-2 mega-form">
                                                        <div class="mb-4 row align-items-center">
                                                            <label class="col-sm-3 col-form-label ">Video
                                                                Provider</label>
                                                            <div class="col-sm-9">
                                                                <select class="js-example-basic-single w-100 form-select border border-secondary" name="video_provider" id="video_provider">
                                                                    <option value="vimeo">Vimeo</option>
                                                                    <option value="youtube">Youtube</option>
                                                                    <option value="tiktok">Tiktok</option>
                                                                    <option value="none">Ninguno</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-center">
                                                            <label class=" col-sm-3 mb-0">Video Link</label>
                                                            <div class="col-sm-9">
                                                                <input class="form-control border border-secondary" id="video_link" name="video_link" type="text" placeholder="Video Link">
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            {{-- Costos*** - precios - %Utiludad*** - $Ganancia***--}}
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-header-2">
                                                        <h5>Costos y Precios</h5>
                                                    </div>

                                                   
                                                        <div class="mb-4 row align-items-center">
                                                            <label class="col-sm-3 ">Costo </label>
                                                            <div class="col-sm-9">
                                                                <input class="form-control border border-secondary" type="text" placeholder="0">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-4 row align-items-center">

                                                            <div class="row pb-3">
                                                                <label class="col-sm-3 ">Precio 1</label>
                                                                <div class="col-sm-4">
                                                                    <input class="form-control border border-secondary" type="text" placeholder="0">
                                                                </div>

                                                                <div class="col-sm-2 mx-1">
                                                                    <label><strong>Utilidad-</strong></label>
                                                                    <span>25%</span>
                                                                </div>
    
                                                                <div class="col-sm-2">
                                                                    <label><strong>Ganancia</strong></label>
                                                                    <span>$5</span>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <label class="col-sm-3">Precio 2</label>
                                                                <div class="col-sm-4">

                                                                    <input class="form-control border border-secondary" type="text" placeholder="0">

                                                                </div>
                                                                <div class="col-sm-2 mx-1">
                                                                    <label style="padding-bottom: -10px"><strong>Utilidad-</strong></label>
                                                                    <span>25%</span>
                                                                </div>
    
                                                                <div class="col-sm-2">
                                                                    <label><strong>Ganancia</strong></label>
                                                                    <span>$5</span>
                                                                </div>
                                                            </div>

                                                            
                                                        </div>
                                                   
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <!-- Botones con el layout que necesitas -->
                                    <div class="row mt-4">
                                            
                                        {{-- Cancelar->lista de proveedores --}}
                                        <div class="col-md-6 col-sm-12 mb-2">
                                            <a href="{{ route('products.index') }}" class="btn btn-secondary w-100">
                                                <i class="ri-close-line"></i> Cancelar
                                            </a>
                                        </div>

                                        {{-- Registrar Productos --}}
                                        <div class="col-md-6 col-sm-12 mb-2">
                                            <button type="submit" class="btn btn-theme w-100">
                                                <i class="ri-add-circle-line px-1"></i> Registrar
                                            </button>
                                        </div>
                                    </div>


                                </form>

                                
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- New Product Add End -->

    <style>
        /* Switch pequeño (versión SM) */
            .switch.sm-switch {
            position: relative;
            display: inline-block;
            width: 42px;  /* Ancho reducido */
            height: 22px; /* Altura reducida */
            }

            .switch.sm-switch input {
            opacity: 0;
            width: 0;
            height: 0;
            }

            .switch.sm-switch .switch-state {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 22px; /* Ajustado a la nueva altura */
            }

            .switch.sm-switch .switch-state:before {
            position: absolute;
            content: "";
            height: 18px;  /* Tamaño reducido */
            width: 18px;   /* Tamaño reducido */
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            }

            .switch.sm-switch input:checked + .switch-state {
            background-color: #2ebe93; /* Color de Bootstrap para elementos activos */
            }

            .switch.sm-switch input:focus + .switch-state {
            box-shadow: 0 0 1px #2ebe93;
            }

            .switch.sm-switch input:checked + .switch-state:before {
            transform: translateX(20px); /* Ajustado al nuevo ancho */
            }
    </style>
    
</div>

@endsection