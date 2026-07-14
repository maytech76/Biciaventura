<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Helpers\DolarHelper;

class ProductController extends Controller
{
    public function index(Request $request){

        Log::info('ProductController@index - Iniciando', ['search' => $request->search]);
        
        try {
            $query = Product::with(['category', 'brand', 'images']);
    
            if ($request->has('search') && !empty($request->search)) {
                Log::debug('ProductController@index - Aplicando búsqueda', ['term' => $request->search]);
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('codebar', 'LIKE', "%{$search}%")
                      ->orWhere('price', 'LIKE', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('brand', function($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
    
            $products = $query->latest()->paginate(10);
            $tasaInfo = DolarHelper::getTasaInfo();
            
            Log::info('ProductController@index - Productos obtenidos', [
                'search_term' => $request->search,
                'page' => $request->page ?? 1
            ]);
    
            return view('admin.products.index', compact('products', 'tasaInfo'));
            
        } catch (\Exception $e) {
            Log::error('ProductController@index - Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function create(){
        
        Log::info('ProductController@create - Iniciando');
        
        try {
            $valueunits = Product::UNITS;
            $categories = Category::all();
            $brands = Brand::all();
            
            Log::debug('ProductController@create - Datos cargados', [
                'categories_count' => $categories->count(),
                'brands_count' => $brands->count(),
                'units' => $valueunits
            ]);
            
            return view('admin.products.create', compact('categories', 'brands', 'valueunits'));
            
        } catch (\Exception $e) {
            Log::error('ProductController@create - Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function store(Request $request){

        Log::info('ProductController@store - Iniciando creación de producto', [
            'request_data' => $request->except(['main_image', 'additional_images'])
        ]);

        try {
            $request->validate([
                
                'category_id' => 'required|exists:categories,id',
                'type' => 'nullable',
                'brand_id' => 'required|exists:brands,id',
                'codebar' => 'nullable|string|max:55|unique:products,codebar',
                'name' => 'required|string|max:255|unique:products,name',
                'description' => 'nullable|string',
                'cost' => 'required|numeric|min:0',
                'utility_percentage' => 'required|numeric|min:0|max:100',
                'stock' => 'nullable|integer', 
                'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:6048',
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6048', // Cambiado de 'images' a 'additional_images'
                'unit' => 'required',
                'video_provider' => 'nullable|in:youtube,vimeo,tiktok,custom,none',
                'video_link' => 'nullable|url',
            ]);

            Log::debug('ProductController@store - Validación pasada');

            $data = $request->only([
                'category_id', 'type', 'brand_id', 'name', 'description', 
                'cost', 'utility_percentage', 'stock', 'unit',
                'video_provider', 'video_link', 'slug', 'codebar',
                'price2', 'utility_percentage2', 'profit2'
            ]);

            $data['interchangeable'] = $request->has('interchangeable') ? 1 : 0;
            $data['refundable'] = $request->has('refundable') ? 1 : 0;
            $data['status'] = $request->has('status');

            Log::debug('ProductController@store - Datos procesados', $data);

            // Calcular precio y ganancia
            $data['profit'] = ($request->cost * $request->utility_percentage) / 100;
            $data['price'] = $request->cost + $data['profit'];
            $data['price2'] = $request->price2 ?? $data['price'];

            Log::debug('ProductController@store - Cálculos financieros', [
                'cost' => $request->cost,
                'utility_percentage' => $request->utility_percentage,
                'profit' => $data['profit'],
                'price' => $data['price']
            ]);

            // Guardar imagen principal en products.main_image
            if ($request->hasFile('main_image')) {
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
                Log::debug('ProductController@store - Imagen principal guardada', [
                    'path' => $data['main_image']
                ]);
            }

            // Crear producto
            Log::debug('ProductController@store - Creando producto en BD');
            $product = Product::create($data);
            Log::info('ProductController@store - Producto creado', ['product_id' => $product->id]);

            // Guardar imágenes adicionales en product_images
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $index => $image) {
                    $imagePath = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $imagePath,
                        'is_primary' => false,
                        'order' => $index
                    ]);
                    
                    Log::debug('ProductController@store - Imagen adicional guardada', [
                        'index' => $index,
                        'path' => $imagePath
                    ]);
                }
                
                Log::info('ProductController@store - Imágenes adicionales procesadas', [
                    'count' => count($request->file('additional_images'))
                ]);
            }

            Log::info('ProductController@store - Producto creado exitosamente', ['product_id' => $product->id]);
            
            return redirect()->route('products.index')
                ->with('success', 'Producto creado exitosamente.');

        } catch (\Exception $e) {
            Log::error('ProductController@store - Error al crear producto', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function show(Product $product)
    {
        Log::info('ProductController@show - Mostrando producto', ['product_id' => $product->id]);
        
        try {
            $product->load(['category', 'brand', 'images']);
            Log::debug('ProductController@show - Producto cargado', [
                'name' => $product->name,
                'images_count' => $product->images->count()
            ]);
            
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
            
        } catch (\Exception $e) {
            Log::error('ProductController@show - Error', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function edit(Product $product)
    {
        Log::info('ProductController@edit - Editando producto', ['product_id' => $product->id]);
        
        try {
            $units = Product::UNITS;
            $product->load('images');
            $categories = Category::where('status', true)->get();
            $brands = Brand::where('status', true)->get();
            
            Log::debug('ProductController@edit - Datos cargados', [
                'product_name' => $product->name,
                'categories_count' => $categories->count(),
                'brands_count' => $brands->count(),
                'images_count' => $product->images->count()
            ]);
            
            return view('admin.products.edit', compact('product', 'categories', 'brands', 'units'));
            
        } catch (\Exception $e) {
            Log::error('ProductController@edit - Error', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function update(Request $request, Product $product){

        Log::info('ProductController@update - Actualizando producto', [
            'product_id' => $product->id,
            'request_data' => $request->except(['main_image', 'additional_images'])
        ]);

        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'type' => 'nullable',
                'brand_id' => 'required|exists:brands,id',
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
                'description' => 'nullable|string',
                'cost' => 'required|numeric|min:0',
                'utility_percentage' => 'required|numeric',
                'stock' => 'nullable|integer', 
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6048',
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6048', // Cambiado de 'images' a 'additional_images'
                'unit' => 'required',
                'video_provider' => 'nullable|in:youtube,vimeo,custom,none',
                'video_link' => 'nullable|url',
            ]);

            Log::debug('ProductController@update - Validación pasada');

            $data = $request->only([
                'category_id', 'brand_id', 'name', 'description', 
                'cost', 'utility_percentage', 'stock', 'unit',
                'video_provider', 'video_link', 'slug', 'codebar',
                'price2', 'utility_percentage2', 'profit2'
            ]);

            $data['interchangeable'] = $request->has('interchangeable') ? 1 : 0;
            $data['refundable'] = $request->has('refundable') ? 1 : 0;
            $data['status'] = $request->has('status');

            // Recalcular precio y ganancia
            $data['profit'] = ($request->cost * $request->utility_percentage) / 100;
            $data['price'] = $request->cost + $data['profit'];
            $data['price2'] = $request->price2 ?? $data['price'];

            Log::debug('ProductController@update - Datos procesados', $data);

            // Procesar nueva imagen principal
            if ($request->hasFile('main_image')) {
                Log::debug('ProductController@update - Procesando nueva imagen principal');
                // Eliminar imagen anterior
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                    Log::debug('ProductController@update - Imagen principal anterior eliminada');
                }

                $data['main_image'] = $request->file('main_image')->store('products', 'public');
                Log::debug('ProductController@update - Nueva imagen principal guardada', ['path' => $data['main_image']]);
            }

            // Actualizar producto
            Log::debug('ProductController@update - Actualizando producto en BD');
            $product->update($data);
            Log::info('ProductController@update - Producto actualizado', ['product_id' => $product->id]);

            // Procesar nuevas imágenes adicionales
            if ($request->hasFile('additional_images')) {
                $currentMaxOrder = $product->images()->max('order') ?? 0;
                $imageCount = count($request->file('additional_images'));
                Log::debug('ProductController@update - Procesando nuevas imágenes adicionales', ['count' => $imageCount]);
                
                foreach ($request->file('additional_images') as $index => $image) {
                    $imagePath = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $imagePath,
                        'is_primary' => false,
                        'order' => $currentMaxOrder + $index + 1
                    ]);
                    Log::debug('ProductController@update - Nueva imagen adicional guardada', ['path' => $imagePath]);
                }
                
                Log::info('ProductController@update - Imágenes adicionales agregadas', [
                    'count' => $imageCount
                ]);
            }

            Log::info('ProductController@update - Producto actualizado exitosamente', ['product_id' => $product->id]);
            
            return redirect()->route('products.index')
                ->with('success', 'Producto actualizado exitosamente.');

        } catch (\Exception $e) {
            Log::error('ProductController@update - Error al actualizar producto', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function destroy(Product $product){
        Log::info('ProductController@destroy - Eliminando producto', ['product_id' => $product->id]);
        
        try {
            // Eliminar imagen principal
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
                Log::debug('ProductController@destroy - Imagen principal eliminada');
            }

            // Eliminar imágenes adicionales
            $additionalImagesCount = $product->images->count();
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
            Log::debug('ProductController@destroy - Imágenes adicionales eliminadas', ['count' => $additionalImagesCount]);

            $product->delete();
            Log::info('ProductController@destroy - Producto eliminado exitosamente', ['product_id' => $product->id]);

            return redirect()->route('products.index')
                ->with('success', 'Producto eliminado exitosamente.');

        } catch (\Exception $e) {
            Log::error('ProductController@destroy - Error al eliminar producto', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function details($id, $slug){

        // Buscar el producto
        $product = Product::where('id', $id)->where('slug', $slug)->firstOrFail();
    
        // Obtener los 6 productos más vendidos (solo tipo PRODUCTO)
        $topSellingProducts = $this->getTopSellingProducts(6);
    
        return view('products.details', compact('product', 'topSellingProducts'));
    }

    
    // Método auxiliar en el mismo controlador
    private function getTopSellingProducts($limit = 6){

        return Product::where('type', 'PRODUCTO')  // Filtro añadido
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take($limit)
            ->get();
    }
    

    public function shop(Request $request){

        // Convertir las categorías en un array si están en formato "1,2,3"
        $categoriesFilter = $request->has('categories')
            ? explode(',', $request->input('categories'))
            : [];

        $brandsFilter = $request->has('brands')
            ? explode(',', $request->input('brands'))
            : [];

        $pricesFilter = $request->has('prices')
            ? explode(',', $request->input('prices'))
            : [];

        $ratingsFilter = $request->has('ratings')
            ? explode(',', $request->input('ratings'))
            : [];

        $search = $request->input('search');

        // Consultar productos con filtro de categorías múltiple
        $products = Product::with('category')
            ->when(!empty($categoriesFilter), function ($query) use ($categoriesFilter) {
                return $query->whereIn('category_id', $categoriesFilter);
            })
            ->when(!empty($brandsFilter), function ($query) use ($brandsFilter) {
                return $query->whereIn('brand_id', $brandsFilter);
            })
            ->when(!empty($pricesFilter), function ($query) use ($pricesFilter) {
                return $query->where(function ($q) use ($pricesFilter) {
                    foreach ($pricesFilter as $priceRange) {
                        [$min, $max] = explode('-', $priceRange);
                        $q->orWhereBetween('price', [(int)$min, (int)$max]);
                    }
                });
            })
            ->when(!empty($ratingsFilter), function ($query) use ($ratingsFilter) {
                return $query->whereIn('id', function ($subQuery) use ($ratingsFilter) {
                    $subQuery->select('product_id')
                        ->from('reviews')
                        ->groupBy('product_id')
                        ->havingRaw('AVG(rating) IN (' . implode(',', array_map('intval', $ratingsFilter)) . ')');
                });
            })
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->paginate(12);

        // Obtener todas las categorías para los filtros
        $categories = Category::all();
        $brands = Brand::all();

        return view('shop.index', compact('products','brands', 'categories', 'categoriesFilter', 'brandsFilter','pricesFilter','ratingsFilter', 'search'));
    }
}