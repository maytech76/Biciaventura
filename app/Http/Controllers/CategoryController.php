<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    
    public function index(){

        $categories = Category::latest()->paginate(4);
        return view('admin.categories.index', compact('categories'));
    }

    
    public function create(){
        
        return view('admin.categories.create');
    }

    
    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'description']);
        $data['slug'] = Str::slug($request->name);

        // Procesar imagen
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = 'category_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('categories', $photoName, 'public');
            $data['photo'] = $photoPath;
        }

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    
    public function show(Category $category){

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'photo_url' => $category->photo_url,
                'created_at' => $category->created_at->format('d/m/Y H:i'),
                'updated_at' => $category->updated_at->format('d/m/Y H:i'),
            ]
        ]);
    }

    
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

   
    public function update(Request $request, Category $category){

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'description']);
        
        // Actualizar slug solo si cambió el nombre
        if ($category->name != $request->name) {
            $data['slug'] = Str::slug($request->name);
        }

        // Procesar nueva imagen
        if ($request->hasFile('photo')) {
            // Eliminar imagen anterior si existe
            if ($category->photo && Storage::disk('public')->exists($category->photo)) {
                Storage::disk('public')->delete($category->photo);
            }

            $photo = $request->file('photo');
            $photoName = 'category_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('categories', $photoName, 'public');
            $data['photo'] = $photoPath;
        }

        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    
    public function destroy(Category $category){
        
        // Eliminar imagen si existe
        if ($category->photo && Storage::disk('public')->exists($category->photo)) {
            Storage::disk('public')->delete($category->photo);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}