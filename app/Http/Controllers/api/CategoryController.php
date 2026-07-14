<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/categories",
     *      operationId="getCategoriesList",
     *      tags={"Categories"},
     *      summary="Obtener listado de categorías",
     *      description="Retorna un listado paginado de todas las categorías",
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Número de página para paginación",
     *          required=false,
     *          @OA\Schema(type="integer", default=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Elementos por página",
     *          required=false,
     *          @OA\Schema(type="integer", default=15)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Electrónica"),
     *                      @OA\Property(property="slug", type="string", example="electronica"),
     *                      @OA\Property(property="description", type="string", example="Productos electrónicos"),
     *                      @OA\Property(property="photo_url", type="string", example="http://dominio.com/storage/categories/category_123456.jpg"),
     *                      @OA\Property(property="created_at", type="string", format="date-time"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time")
     *                  )
     *              ),
     *              @OA\Property(property="links", type="object"),
     *              @OA\Property(property="meta", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error del servidor"
     *      )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $categories = Category::latest()->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $categories->items(),
                'links' => [
                    'first' => $categories->url(1),
                    'last' => $categories->url($categories->lastPage()),
                    'prev' => $categories->previousPageUrl(),
                    'next' => $categories->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $categories->currentPage(),
                    'from' => $categories->firstItem(),
                    'last_page' => $categories->lastPage(),
                    'path' => $categories->path(),
                    'per_page' => $categories->perPage(),
                    'to' => $categories->lastItem(),
                    'total' => $categories->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las categorías',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/categories/{id}",
     *      operationId="getCategoryById",
     *      tags={"Categories"},
     *      summary="Obtener una categoría específica",
     *      description="Retorna los detalles de una categoría por su ID",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID de la categoría",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Electrónica"),
     *                  @OA\Property(property="slug", type="string", example="electronica"),
     *                  @OA\Property(property="description", type="string", example="Productos electrónicos"),
     *                  @OA\Property(property="photo_url", type="string", example="http://dominio.com/storage/categories/category_123456.jpg"),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Categoría no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Categoría no encontrada")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error del servidor"
     *      )
     * )
     */
    public function show($id)
    {
        try {
            $category = Category::find($id);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoría no encontrada'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'photo_url' => $category->photo ? Storage::url($category->photo) : null,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/categories",
     *      operationId="storeCategory",
     *      tags={"Categories"},
     *      summary="Crear una nueva categoría",
     *      description="Crea una nueva categoría y retorna sus datos",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos de la categoría",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name"},
     *                  @OA\Property(property="name", type="string", example="Electrónica"),
     *                  @OA\Property(property="description", type="string", example="Productos electrónicos"),
     *                  @OA\Property(
     *                      property="photo",
     *                      type="string",
     *                      format="binary",
     *                      description="Imagen de la categoría (max: 2MB)"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Categoría creada exitosamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Categoría creada exitosamente"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Electrónica"),
     *                  @OA\Property(property="slug", type="string", example="electronica"),
     *                  @OA\Property(property="description", type="string", example="Productos electrónicos"),
     *                  @OA\Property(property="photo_url", type="string", example="http://dominio.com/storage/categories/category_123456.jpg"),
     *                  @OA\Property(property="created_at", type="string", format="date-time")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado - Token requerido",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Error de validación"),
     *              @OA\Property(property="errors", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error del servidor"
     *      )
     * )
     */
    public function store(Request $request)
    {
        // Verificar autenticación (ya está protegido por middleware)
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Token requerido.'
            ], 401);
        }
        
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $data = $request->only(['name', 'description']);
            $data['slug'] = Str::slug($request->name);
            
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = 'category_' . time() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('categories', $photoName, 'public');
                $data['photo'] = $photoPath;
            }
            
            $category = Category::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'photo_url' => $category->photo ? Storage::url($category->photo) : null,
                    'created_at' => $category->created_at,
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/categories/{id}",
     *      operationId="updateCategory",
     *      tags={"Categories"},
     *      summary="Actualizar una categoría existente",
     *      description="Actualiza una categoría por su ID y retorna los datos actualizados",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID de la categoría a actualizar",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Datos a actualizar",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Electrónica Actualizada"),
     *              @OA\Property(property="description", type="string", example="Descripción actualizada")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Categoría actualizada exitosamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Categoría actualizada exitosamente"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Electrónica Actualizada"),
     *                  @OA\Property(property="slug", type="string", example="electronica-actualizada"),
     *                  @OA\Property(property="description", type="string", example="Descripción actualizada"),
     *                  @OA\Property(property="photo_url", type="string", example="http://dominio.com/storage/categories/category_123456.jpg"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado - Token requerido"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Categoría no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Categoría no encontrada")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error del servidor"
     *      )
     * )
     */
    public function update(Request $request, $id)
{
    // Verificar autenticación
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'No autenticado. Token requerido.'
        ], 401);
    }
    
    try {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
        
        // Determinar tipo de contenido
        $isMultipart = $request->isMethod('post') || 
                       $request->header('Content-Type') == 'multipart/form-data';
        
        if ($isMultipart) {
            // Para multipart/form-data, validar campos de texto
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        } else {
            // Para JSON
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string'
            ]);
        }
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $data = [];
        
        if ($request->has('name')) {
            $data['name'] = $request->name;
            if ($category->name != $request->name) {
                $data['slug'] = Str::slug($request->name);
            }
        }
        
        if ($request->has('description')) {
            $data['description'] = $request->description;
        }
        
        if ($request->hasFile('photo')) {
            if ($category->photo && Storage::disk('public')->exists($category->photo)) {
                Storage::disk('public')->delete($category->photo);
            }
            
            $photo = $request->file('photo');
            $photoName = 'category_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('categories', $photoName, 'public');
            $data['photo'] = $photoPath;
        }
        
        // Solo actualizar si hay datos
        if (!empty($data)) {
            $category->update($data);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'photo_url' => $category->photo ? Storage::url($category->photo) : null,
                'updated_at' => $category->updated_at,
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la categoría',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * @OA\Post(
     *      path="/api/categories/{id}/photo",
     *      operationId="updateCategoryPhoto",
     *      tags={"Categories"},
     *      summary="Actualizar imagen de categoría",
     *      description="Actualiza solo la imagen de una categoría existente",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID de la categoría",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Nueva imagen de la categoría",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"photo"},
     *                  @OA\Property(
     *                      property="photo",
     *                      type="string",
     *                      format="binary",
     *                      description="Imagen de la categoría (max: 2MB)"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Imagen actualizada exitosamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Imagen actualizada exitosamente"),
     *              @OA\Property(property="photo_url", type="string", example="http://dominio.com/storage/categories/category_123456.jpg")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado - Token requerido"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Categoría no encontrada"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error del servidor"
     *      )
     * )
     */
    public function updatePhoto(Request $request, $id){
        // Verificar autenticación
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Token requerido.'
            ], 401);
        }
        
        try {
            $category = Category::find($id);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoría no encontrada'
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Eliminar imagen anterior si existe
            if ($category->photo && Storage::disk('public')->exists($category->photo)) {
                Storage::disk('public')->delete($category->photo);
            }
            
            // Subir nueva imagen
            $photo = $request->file('photo');
            $photoName = 'category_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('categories', $photoName, 'public');
            
            $category->update(['photo' => $photoPath]);
            
            return response()->json([
                'success' => true,
                'message' => 'Imagen actualizada exitosamente',
                'photo_url' => Storage::url($photoPath)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la imagen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/categories/{id}",
     *      operationId="deleteCategory",
     *      tags={"Categories"},
     *      summary="Eliminar una categoría",
     *      description="Elimina una categoría por su ID",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID de la categoría a eliminar",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Categoría eliminada exitosamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Categoría eliminada exitosamente")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado - Token requerido"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Categoría no encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Categoría no encontrada")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error del servidor"
     *      )
     * )
     */
    public function destroy($id)
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Token requerido.'
            ], 401);
        }
        
        try {
            $category = Category::find($id);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoría no encontrada'
                ], 404);
            }
            
            if ($category->photo && Storage::disk('public')->exists($category->photo)) {
                Storage::disk('public')->delete($category->photo);
            }
            
            $category->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}