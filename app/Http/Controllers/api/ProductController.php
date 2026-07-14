<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="Operaciones para gestionar productos"
 * )
 * 
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"category_id", "brand_id", "name", "price", "stock", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="category_id", type="integer", example=1),
 *     @OA\Property(property="brand_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Smartphone X"),
 *     @OA\Property(property="slug", type="string", example="smartphone-x"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Ultimo modelo con camara 108MP"),
 *     @OA\Property(property="price", type="number", format="float", example=999.99),
 *     @OA\Property(property="price2", type="number", format="float", nullable=true, example=899.99),
 *     @OA\Property(property="stock", type="integer", example=50),
 *     @OA\Property(property="image", type="string", nullable=true, example="smartphone-x.jpg"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     operationId="getProductsList",
     *     summary="Listar todos los productos",
     *     description="Retorna un listado de productos con opciones de filtrado",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filtrar por ID de categoria",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="brand_id",
     *         in="query",
     *         description="Filtrar por ID de marca",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Buscar por nombre o descripcion",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por estado (active, inactive)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"active", "inactive"})
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numero de pagina para paginacion",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Elementos por pagina",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al obtener los productos")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with(['category', 'brand']);

            // Filtros
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('brand_id')) {
                $query->where('brand_id', $request->brand_id);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Paginacion
            $perPage = $request->input('per_page', 15);
            $products = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     operationId="getProductById",
     *     summary="Obtener un producto especifico",
     *     description="Retorna los detalles de un producto por su ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Producto no encontrado")
 *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $product = Product::with(['category', 'brand'])->find($id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // METODOS PROTEGIDOS (requieren autenticacion Sanctum)
    // ============================================

    /**
     * @OA\Post(
     *     path="/api/products",
     *     operationId="storeProduct",
     *     summary="Crear un nuevo producto",
     *     description="Crea un nuevo producto (requiere autenticacion)",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del producto",
     *         @OA\JsonContent(
     *             required={"category_id", "brand_id", "name", "price", "stock", "status"},
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Smartphone X"),
     *             @OA\Property(property="slug", type="string", example="smartphone-x", nullable=true),
     *             @OA\Property(property="description", type="string", example="Ultimo modelo con camara 108MP", nullable=true),
     *             @OA\Property(property="price", type="number", format="float", example=999.99),
     *             @OA\Property(property="price2", type="number", format="float", example=899.99, nullable=true),
     *             @OA\Property(property="stock", type="integer", example=50),
     *             @OA\Property(property="image", type="string", example="smartphone-x.jpg", nullable=true),
     *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Product"),
     *             @OA\Property(property="message", type="string", example="Producto creado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado - Token requerido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validacion",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error en los datos proporcionados"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Verificar autenticacion
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Token requerido.'
            ], 401);
        }
        
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|unique:products,slug',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'price2' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validacion',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $product = Product::create($data);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Producto creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     operationId="updateProduct",
     *     summary="Actualizar un producto existente",
     *     description="Actualiza un producto por su ID (requiere autenticacion)",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos a actualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Smartphone X Pro"),
     *             @OA\Property(property="slug", type="string", example="smartphone-x-pro", nullable=true),
     *             @OA\Property(property="description", type="string", example="Modelo actualizado", nullable=true),
     *             @OA\Property(property="price", type="number", format="float", example=1099.99),
     *             @OA\Property(property="price2", type="number", format="float", example=999.99, nullable=true),
     *             @OA\Property(property="stock", type="integer", example=30),
     *             @OA\Property(property="image", type="string", example="smartphone-x-pro.jpg", nullable=true),
     *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Product"),
     *             @OA\Property(property="message", type="string", example="Producto actualizado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado - Token requerido"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validacion"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Verificar autenticacion
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Token requerido.'
            ], 401);
        }
        
        try {
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|unique:products,slug,' . $product->id,
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'price2' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validacion',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            if (empty($data['slug']) && isset($data['name']) && $product->name != $data['name']) {
                $data['slug'] = Str::slug($data['name']);
            }

            $product->update($data);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Producto actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     operationId="deleteProduct",
     *     summary="Eliminar un producto",
     *     description="Elimina un producto por su ID (requiere autenticacion)",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Producto eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado - Token requerido"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function destroy($id)
    {
        // Verificar autenticacion
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Token requerido.'
            ], 401);
        }
        
        try {
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }
            
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}