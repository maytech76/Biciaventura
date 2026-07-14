@extends('admin.layouts.master')

@section('content')
@section('title', 'New product')

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
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">

                                            {{-- Nombre del Producto --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class=" col-sm-3 mb-0">Nombre</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control border border-secondary" type="text" id="name" name="name" placeholder="Agregar Nombre del producto" value="{{ old('name') }}">
                                                </div>
                                            </div>

                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Tipo</label>
                                                <div class="col-sm-9">
                                                    <select class="js-example-basic-single w-100 form-select border border-secondary" name="type" id="type">
                                                        <option value="PRODUCTO" {{ old('type') == 'PRODUCTO' ? 'selected' : '' }}>PRODUCTO</option>
                                                        <option value="SERVICIO" {{ old('type') == 'SERVICIO' ? 'selected' : '' }}>SERVICIO</option>
                                                        <option value="REPUESTO" {{ old('type') == 'REPUESTO' ? 'selected' : '' }}>REPUESTO</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Categorias --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Category</label>
                                                <div class="col-sm-9">
                                                    <select class="js-example-basic-single w-100 form-select border border-secondary" name="category_id" id="category_id">
                                                        <option value="">Seleccione una Categoria</option>
                                                        @foreach ($categories as $category)
                                                         <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Marca del producto --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Marca</label>
                                                <div class="col-sm-9">
                                                    <select class="js-example-basic-single w-100 form-select border border-secondary" name="brand_id" id="brand_id">
                                                        <option value="">Selecciona un Marca</option>
                                                        @foreach ($brands as $brand)
                                                           <option value="{{$brand->id}}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{$brand->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Nombre de la Unidad --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Unidad </label>
                                                <div class="col-sm-9">
                                                    <select name="unit" id="unit" class="form-select border border-secondary">
                                                        <option value="">Seleccione una unidad</option>
                                                        <option value="UND" {{ old('unit') == 'UND' ? 'selected' : '' }}>UNIDAD</option>
                                                        <option value="CAJA" {{ old('unit') == 'CAJA' ? 'selected' : '' }}>CAJA</option>
                                                        <option value="PAR" {{ old('unit') == 'PAR' ? 'selected' : '' }}>PAR</option>
                                                        <option value="KIT" {{ old('unit') == 'KIT' ? 'selected' : '' }}>KIT</option>
                                                        <option value="SET" {{ old('unit') == 'SET' ? 'selected' : '' }}>SET</option>
                                                        <option value="JUEGO" {{ old('unit') == 'JUEGO' ? 'selected' : '' }}>JUEGO</option>
                                                        <option value="PIEZA" {{ old('unit') == 'PIEZA' ? 'selected' : '' }}>PIEZA</option>
                                                        <option value="MTS" {{ old('unit') == 'MTS' ? 'selected' : '' }}>METRO</option>
                                                        <option value="RECARGA" {{ old('unit') == 'RECARGA' ? 'selected' : '' }}>RECARGA</option>
                                                        <option value="KG" {{ old('unit') == 'KG' ? 'selected' : '' }}>KILOS</option>
                                                        <option value="LTS" {{ old('unit') == 'LTS' ? 'selected' : '' }}>LITROS</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Slug del producto --}}
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label ">Slug</label>
                                                <div class="col-sm-9">
                                                    <div class="bs-example">
                                                        <input type="text" class="form-control border border-secondary" placeholder="Type tag & hit enter" id="slug" name="slug" data-role="tagsinput" value="{{ old('slug') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Es intercambiable --}}
                                            {{-- <div class="mb-4 row align-items-center">
                                                <label class="col-sm-3 col-form-label">Intercambiable</label>
                                                <div class="col-sm-9">
                                                    <label class="switch sm-switch">
                                                        <input type="checkbox" name="interchangeable" id="interchangeable" value="1" 
                                                            {{ old('interchangeable') ? 'checked' : '' }}>
                                                        <span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </div> --}}
                                            
                                            {{-- Es reembolsable --}}
                                            <div class="row align-items-center">
                                                <label class="col-sm-3 col-form-label">Reembolso</label>
                                                <div class="col-sm-9">
                                                    <!-- Campo hidden con valor 0 por defecto -->
                                                    <label class="switch sm-switch">
                                                        <input type="checkbox" name="refundable" id="refundable" value="1" 
                                                            {{ old('refundable') ? 'checked' : '' }}>
                                                        <span class="switch-state"></span>
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
                                                                    <textarea class="form-control w-100 form-control border border-secondary" name="description" id="description" cols="40" rows="4">{{ old('description') }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Imagenes --}}
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-header-2">
                                                        <h5>Imagenes del Producto</h5>
                                                    </div>

                                                    <div class="row align-items-center mb-2">
                                                        <label class="col-sm-3 col-form-label">Imagen Portada</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control form-choose" type="file" id="main_image" name="main_image">
                                                        </div>
                                                    </div> 

                                                    <div class="mb-4 row align-items-center">
                                                        <label class="col-sm-3 col-form-label">+ Imagenes</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control form-choose" type="file" id="additional_images" name="additional_images[]" multiple="">
                                                        </div>
                                                    </div>

                                                    

                                                    {{-- Codigo de Barra --}}
                                                    <div class="mt-4 row align-items-center">
                                                        <label class="col-sm-3 col-form-label ">Codigo de Barra</label>
                                                        <div class="col-sm-9">
                                                            <div class="bs-example">
                                                                <input type="text" class="form-control border border-secondary" placeholder="Ingrese Codigo de Barra" id="codebar" name="codebar" data-role="tagsinput" value="{{ old('codebar') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                             {{-- Link de Video --}}
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-header-2">
                                                        <h5>Video de Producto</h5>
                                                    </div>

                                                    <div class="mb-4 row align-items-center">
                                                        <label class="col-sm-3 col-form-label ">Video Provider</label>
                                                        <div class="col-sm-9">
                                                            <select class="js-example-basic-single w-100 form-select border border-secondary" name="video_provider" id="video_provider">
                                                                <option value="vimeo" {{ old('video_provider') == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                                                <option value="youtube" {{ old('video_provider') == 'youtube' ? 'selected' : '' }}>Youtube</option>
                                                                <option value="tiktok" {{ old('video_provider') == 'tiktok' ? 'selected' : '' }}>Tiktok</option>
                                                                <option value="none" {{ old('video_provider') == 'none' ? 'selected' : '' }}>Ninguno</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mb-4 row align-items-center">
                                                        <label class=" col-sm-3">Video Link</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control border border-secondary" id="video_link" name="video_link" type="text" placeholder="Video Link" value="{{ old('video_link') }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-4 row align-items-center">
                                                        <label class=" col-sm-3">Existencia</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control border border-secondary" id="stock" name="stock" type="text" placeholder="Ingresar Existencia" value="{{ old('stock') }}">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                       {{-- Costo-Utilidad-Precio --}}
                                        <div class="col-md-6">
                                            {{-- Costos - precios - %Utiludad - $Ganancia--}}
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-header-2">
                                                        <h5>Costos y Precios</h5>
                                                    </div>

                                                    <div class="mb-4 row align-items-center">
                                                        <label class="col-sm-3 text-danger">Costo</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control border border-secondary" type="text" step="0.01" name="cost" id="cost" placeholder="0" value="{{ old('cost') }}">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-4 row align-items-center">
                                                        <div class="row pb-3">
                                                            <label class="col-sm-2 mt-4">Precio 1</label>
                                                            
                                                            <div class="col-sm-3 mx-1">
                                                                <label>Utilidad</label>
                                                                <span><input class="form-control border border-secondary" type="text" step="0.01" id="utility_percentage" name="utility_percentage" value="{{ old('utility_percentage') }}"></span>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label>Ganancia</label>
                                                                <span><input class="form-control border border-secondary" type="text" step="0.01" id="profit" name="profit" value="{{ old('profit') }}"></span>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="fw-bold">Precio 1</label>
                                                                <input class="form-control border border-secondary" type="text" step="0.01" name="price" id="price" placeholder="0" value="{{ old('price') }}">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <label class="col-sm-2 mt-4">Precio 2</label>
                                                            
                                                            <div class="col-sm-3 mx-1">
                                                                <label style="padding-bottom: -10px">Utilidad</label>
                                                                <span><input class="form-control border border-secondary" type="text" step="0.01" id="utility_percentage2" name="utility_percentage2" value="{{ old('utility_percentage2') }}"></span>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label>Ganancia</label>
                                                                <span><input class="form-control border border-secondary" type="text" step="0.01" id="profit2" name="profit2" value="{{ old('profit2') }}"></span>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label class="fw-bold">Precio2</label>
                                                                <input class="form-control border border-secondary" type="text" step="0.01" name="price2" id="price2" placeholder="0" value="{{ old('price2') }}">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del formulario
        const costInput = document.getElementById('cost');
        const utilityInput = document.getElementById('utility_percentage');
        const profitInput = document.getElementById('profit');
        const priceInput = document.getElementById('price');
        const utilityInput2 = document.getElementById('utility_percentage2');
        const profitInput2 = document.getElementById('profit2');
        const priceInput2 = document.getElementById('price2');
    
        // FÓRMULA CORREGIDA: Markup sobre el costo
        // Precio = Costo / (1 - (Utilidad/100))
        
        // Función para calcular basado en costo y utilidad (MARKUP)
        function calculateFromCostAndUtility(cost, utility, profitElement, priceElement) {
            if (utility >= 100) {
                // Evitar división por cero o negativa
                utility = 99.99;
                utilityInput.value = utility.toFixed(2);
            }
            
            const price = cost / (1 - (utility / 100));
            const profit = price - cost;
            
            profitElement.value = profit.toFixed(2);
            priceElement.value = price.toFixed(2);
        }
    
        // Función para calcular basado en costo y precio
        function calculateFromCostAndPrice(cost, price, profitElement, utilityElement) {
            const profit = price - cost;
            // Fórmula inversa: Utilidad = 1 - (Costo / Precio)
            const utility = cost > 0 ? (1 - (cost / price)) * 100 : 0;
            
            profitElement.value = profit.toFixed(2);
            utilityElement.value = utility.toFixed(2);
        }
    
        // Función para calcular basado en costo y ganancia
        function calculateFromCostAndProfit(cost, profit, utilityElement, priceElement) {
            const price = cost + profit;
            // Fórmula inversa: Utilidad = 1 - (Costo / Precio)
            const utility = cost > 0 ? (1 - (cost / price)) * 100 : 0;
            
            utilityElement.value = utility.toFixed(2);
            priceElement.value = price.toFixed(2);
        }
    
        // Función principal para Precio 1
        function handlePrice1Calculations() {
            const cost = parseFloat(costInput.value) || 0;
            const utility = parseFloat(utilityInput.value) || 0;
            const profit = parseFloat(profitInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
    
            // Determinar qué campo fue modificado
            const activeElement = document.activeElement;
    
            if (activeElement === utilityInput && cost > 0) {
                // Caso 1: Usuario ingresa utilidad
                calculateFromCostAndUtility(cost, utility, profitInput, priceInput);
            } else if (activeElement === priceInput && cost > 0) {
                // Caso 2: Usuario ingresa precio
                calculateFromCostAndPrice(cost, price, profitInput, utilityInput);
            } else if (activeElement === profitInput && cost > 0) {
                // Caso 3: Usuario ingresa ganancia
                calculateFromCostAndProfit(cost, profit, utilityInput, priceInput);
            }
        }
    
        // Función principal para Precio 2
        function handlePrice2Calculations() {
            const cost = parseFloat(costInput.value) || 0;
            const utility2 = parseFloat(utilityInput2.value) || 0;
            const profit2 = parseFloat(profitInput2.value) || 0;
            const price2 = parseFloat(priceInput2.value) || 0;
    
            // Determinar qué campo fue modificado
            const activeElement = document.activeElement;
    
            if (activeElement === utilityInput2 && cost > 0) {
                // Caso 1: Usuario ingresa utilidad para precio 2
                calculateFromCostAndUtility(cost, utility2, profitInput2, priceInput2);
            } else if (activeElement === priceInput2 && cost > 0) {
                // Caso 2: Usuario ingresa precio 2
                calculateFromCostAndPrice(cost, price2, profitInput2, utilityInput2);
            } else if (activeElement === profitInput2 && cost > 0) {
                // Caso 3: Usuario ingresa ganancia para precio 2
                calculateFromCostAndProfit(cost, profit2, utilityInput2, priceInput2);
            }
        }
    
        // Event listeners para Precio 1
        utilityInput.addEventListener('input', handlePrice1Calculations);
        profitInput.addEventListener('input', handlePrice1Calculations);
        priceInput.addEventListener('input', handlePrice1Calculations);
    
        // Event listeners para Precio 2
        utilityInput2.addEventListener('input', handlePrice2Calculations);
        profitInput2.addEventListener('input', handlePrice2Calculations);
        priceInput2.addEventListener('input', handlePrice2Calculations);
    
        // Event listener para costo (afecta ambos precios)
        costInput.addEventListener('input', function() {
            const cost = parseFloat(costInput.value) || 0;
            
            // Recalcular Precio 1 si hay datos
            const utility1 = parseFloat(utilityInput.value) || 0;
            const profit1 = parseFloat(profitInput.value) || 0;
            const price1 = parseFloat(priceInput.value) || 0;
    
            if (utility1 > 0) {
                calculateFromCostAndUtility(cost, utility1, profitInput, priceInput);
            } else if (profit1 > 0) {
                calculateFromCostAndProfit(cost, profit1, utilityInput, priceInput);
            } else if (price1 > 0) {
                calculateFromCostAndPrice(cost, price1, profitInput, utilityInput);
            }
    
            // Recalcular Precio 2 si hay datos
            const utility2 = parseFloat(utilityInput2.value) || 0;
            const profit2 = parseFloat(profitInput2.value) || 0;
            const price2 = parseFloat(priceInput2.value) || 0;
    
            if (utility2 > 0) {
                calculateFromCostAndUtility(cost, utility2, profitInput2, priceInput2);
            } else if (profit2 > 0) {
                calculateFromCostAndProfit(cost, profit2, utilityInput2, priceInput2);
            } else if (price2 > 0) {
                calculateFromCostAndPrice(cost, price2, profitInput2, utilityInput2);
            }
        });
    
        // Función para sincronizar Precio 2 con Precio 1 si está vacío
        function syncPrice2IfEmpty() {
            const price1 = parseFloat(priceInput.value) || 0;
            const price2 = parseFloat(priceInput2.value) || 0;
            const utility2 = parseFloat(utilityInput2.value) || 0;
            const profit2 = parseFloat(profitInput2.value) || 0;
    
            // Si Precio 2 está vacío pero Precio 1 tiene valor, copiar Precio 1
            if (price1 > 0 && price2 === 0 && utility2 === 0 && profit2 === 0) {
                priceInput2.value = price1.toFixed(2);
                // Calcular utilidad y ganancia para Precio 2
                const cost = parseFloat(costInput.value) || 0;
                if (cost > 0) {
                    calculateFromCostAndPrice(cost, price1, profitInput2, utilityInput2);
                }
            }
        }
    
        // Sincronizar Precio 2 cuando se calcula Precio 1
        utilityInput.addEventListener('blur', syncPrice2IfEmpty);
        profitInput.addEventListener('blur', syncPrice2IfEmpty);
        priceInput.addEventListener('blur', syncPrice2IfEmpty);
    
        // Validación para evitar valores negativos y utilidad >= 100%
        function validateInputs(input) {
            const value = parseFloat(input.value) || 0;
            
            if (value < 0) {
                input.value = 0;
            }
            
            // Validación especial para utilidad (no puede ser 100% o más en markup)
            if ((input === utilityInput || input === utilityInput2) && value >= 100) {
                input.value = 99.99;
            }
        }
    
        // Aplicar validación a todos los campos numéricos
        const numberInputs = [costInput, utilityInput, profitInput, priceInput, utilityInput2, profitInput2, priceInput2];
        numberInputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateInputs(this);
                // Recalcular después de validar
                if (this === utilityInput || this === utilityInput2) {
                    handlePrice1Calculations();
                    handlePrice2Calculations();
                }
            });
        });
    
        // Ejemplo de demostración automática
        function showExample() {
            console.log("Ejemplo con Costo: 85 y Utilidad: 30%");
            console.log("Fórmula: Precio = 85 / (1 - 0.30) = 85 / 0.70 = 121.428");
        }
        
        // Mostrar ejemplo en consola (opcional)
        showExample();
    });
</script>


    
