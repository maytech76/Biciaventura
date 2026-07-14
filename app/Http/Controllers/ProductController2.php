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
                    ->orWhereHas('category', function($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
                });
            }
    
            $products = $query->latest()->paginate(10);
            
            // SOLUCIÓN TEMPORAL: Log simple sin métodos problemáticos
            Log::info('ProductController@index - Productos obtenidos', [
                'search_term' => $request->search,
                'page' => $request->page ?? 1
            ]);
    
            return view('admin.products.index', compact('products'));
            
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
            
            return view('admin/products.create', compact('categories', 'brands', 'valueunits'));
            
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
            'request_data' => $request->except(['main_image', 'cover_image', 'images'])
        ]);

        try {
            $request->validate([
                
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'name' => 'required|string|max:255|unique:products,name',
                'description' => 'nullable|string',
                'cost' => 'required|numeric|min:0',
                'utility_percentage' => 'required|numeric|min:0|max:100',
                'stock' => 'required|integer|min:0', 
                'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'unit' => 'required',
                'video_provider' => 'required|in:youtube,vimeo,custom,none',
                'video_link' => 'nullable|url|required_if:video_provider,youtube,vimeo,custom',
            ]);

            Log::debug('ProductController@store - Validación pasada');

            $data = $request->only([
                'category_id', 'brand_id', 'name', 'description', 
                'cost', 'utility_percentage', 'stock', 'unit',
                'video_provider', 'video_link'
            ]);

            $data['interchangeable'] = $request->has('interchangeable');
            $data['refundable'] = $request->has('refundable');
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

            // Procesar imagen principal
            if ($request->hasFile('main_image')) {
                Log::debug('ProductController@store - Procesando imagen principal');
                $mainImage = $request->file('main_image');
                $mainImageName = 'product_main_' . time() . '.' . $mainImage->getClientOriginalExtension();
                $mainImagePath = $mainImage->storeAs('products', $mainImageName, 'public');
                $data['main_image'] = $mainImagePath;
                Log::debug('ProductController@store - Imagen principal guardada', ['path' => $mainImagePath]);
            }

            // Procesar imagen de portada
             if ($request->hasFile('cover_image')) {
                Log::debug('ProductController@store - Procesando imagen de portada');
                $coverImage = $request->file('cover_image');
                $coverImageName = 'product_cover_' . time() . '.' . $coverImage->getClientOriginalExtension();
                $coverImagePath = $coverImage->storeAs('products', $coverImageName, 'public');
                $data['cover_image'] = $coverImagePath;
                Log::debug('ProductController@store - Imagen de portada guardada', ['path' => $coverImagePath]);
            } 

            // Crear producto
            Log::debug('ProductController@store - Creando producto en BD');
            $product = Product::create($data);
            Log::info('ProductController@store - Producto creado', ['product_id' => $product->id]);

            // Procesar imágenes adicionales
            if ($request->hasFile('images')) {
                $imageCount = count($request->file('images'));
                Log::debug('ProductController@store - Procesando imágenes adicionales', ['count' => $imageCount]);
                
                foreach ($request->file('images') as $key => $image) {
                    $imageName = 'product_' . $product->id . '_' . time() . '_' . $key . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products/gallery', $imageName, 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'sort_order' => $key,
                        'is_featured' => $key === 0
                    ]);
                    Log::debug('ProductController@store - Imagen adicional guardada', ['path' => $imagePath]);
                }
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

    public function show(Product $product){

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

    public function edit(Product $product){

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
            'request_data' => $request->except(['main_image', 'cover_image', 'images'])
        ]);

        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
                'description' => 'nullable|string',
                'cost' => 'required|numeric|min:0',
                'utility_percentage' => 'nullable|numeric|min:0|max:100',
                'stock' => 'required|integer|min:0',
                'status' => 'sometimes|string',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'unit' => 'required',
                'video_provider' => 'nullable|in:youtube,vimeo,custom,none',
                'video_link' => 'nullable|url|required_if:video_provider,youtube,vimeo,custom',
            ]);

            Log::debug('ProductController@update - Validación pasada');

            // Agrega un valor por defecto para status si no viene en la request
            if (!isset($validated['status'])) {
                $validated['status'] = 'active'; // o el valor por defecto que uses
            }

            $data = $request->only([
                'category_id', 'brand_id', 'name', 'description', 
                'cost', 'utility_percentage', 'stock', 'unit',
                'video_provider', 'video_link'
            ]);

            $data['interchangeable'] = $request->has('interchangeable');
            $data['refundable'] = $request->has('refundable');
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

                $mainImage = $request->file('main_image');
                $mainImageName = 'product_main_' . time() . '.' . $mainImage->getClientOriginalExtension();
                $mainImagePath = $mainImage->storeAs('products', $mainImageName, 'public');
                $data['main_image'] = $mainImagePath;
                Log::debug('ProductController@update - Nueva imagen principal guardada', ['path' => $mainImagePath]);
            }

            // Procesar nueva imagen de portada
            if ($request->hasFile('cover_image')) {
                Log::debug('ProductController@update - Procesando nueva imagen de portada');
                // Eliminar imagen anterior
                if ($product->cover_image) {
                    Storage::disk('public')->delete($product->cover_image);
                    Log::debug('ProductController@update - Imagen de portada anterior eliminada');
                }

                $coverImage = $request->file('cover_image');
                $coverImageName = 'product_cover_' . time() . '.' . $coverImage->getClientOriginalExtension();
                $coverImagePath = $coverImage->storeAs('products', $coverImageName, 'public');
                $data['cover_image'] = $coverImagePath;
                Log::debug('ProductController@update - Nueva imagen de portada guardada', ['path' => $coverImagePath]);
            }

            // Eliminar imagen de portada si se solicita
            if ($request->has('remove_cover_image') && $product->cover_image) {
                Log::debug('ProductController@update - Eliminando imagen de portada por solicitud');
                Storage::disk('public')->delete($product->cover_image);
                $data['cover_image'] = null;
            }

            // Actualizar producto
            Log::debug('ProductController@update - Actualizando producto en BD');
            $product->update($data);
            Log::info('ProductController@update - Producto actualizado', ['product_id' => $product->id]);

            // Procesar nuevas imágenes adicionales
            if ($request->hasFile('images')) {
                $lastSortOrder = $product->images()->max('sort_order') ?? 0;
                $imageCount = count($request->file('images'));
                Log::debug('ProductController@update - Procesando nuevas imágenes adicionales', ['count' => $imageCount]);
                
                foreach ($request->file('images') as $key => $image) {
                    $imageName = 'product_' . $product->id . '_' . time() . '_' . $key . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products/gallery', $imageName, 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'sort_order' => $lastSortOrder + $key + 1,
                        'is_featured' => false
                    ]);
                    Log::debug('ProductController@update - Nueva imagen adicional guardada', ['path' => $imagePath]);
                }
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
            // Eliminar imágenes principales
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
                Log::debug('ProductController@destroy - Imagen principal eliminada');
            }
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
                Log::debug('ProductController@destroy - Imagen de portada eliminada');
            }

            // Eliminar imágenes adicionales
            $additionalImagesCount = $product->images->count();
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
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

        Log::info('ProductController@details - Mostrando detalles', ['id' => $id, 'slug' => $slug]);
        
        try {
            // Buscar el producto
            $product = Product::where('id', $id)->where('slug', $slug)->firstOrFail();
            Log::debug('ProductController@details - Producto encontrado', ['name' => $product->name]);

            // Obtener los 6 productos más vendidos
            $topSellingProducts = $this->getTopSellingProducts(6);
            Log::debug('ProductController@details - Productos más vendidos obtenidos', ['count' => $topSellingProducts->count()]);

            return view('products.details', compact('product', 'topSellingProducts'));
            
        } catch (\Exception $e) {
            Log::error('ProductController@details - Error', [
                'id' => $id,
                'slug' => $slug,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function getTopSellingProducts($limit = 6){

        Log::debug('ProductController@getTopSellingProducts - Obteniendo productos más vendidos', ['limit' => $limit]);
        
        try {
            $products = Product::withCount('orderItems')
                ->orderByDesc('order_items_count')
                ->take($limit)
                ->get();
                
            Log::debug('ProductController@getTopSellingProducts - Productos obtenidos', ['count' => $products->count()]);
            
            return $products;
            
        } catch (\Exception $e) {
            Log::error('ProductController@getTopSellingProducts - Error', [
                'limit' => $limit,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function shop(Request $request){
        
        Log::info('ProductController@shop - Mostrando tienda', [
            'search' => $request->search,
            'categories' => $request->categories
        ]);

        try {
            // ... tu código existente de filtros ...

            $products = Product::with('category')
                // ... tus filtros ...
                ->paginate(12);

            // SOLUCIÓN TEMPORAL
            Log::info('ProductController@shop - Productos obtenidos', [
                'search' => $request->search,
                'categories_filter' => $request->categories,
                'page' => $request->page ?? 1
            ]);

            // ... resto del código ...

        } catch (\Exception $e) {
            Log::error('ProductController@shop - Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

}