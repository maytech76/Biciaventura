<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductImage $productImage)
    {
        // Eliminar archivo físico
        if ($productImage->image_path) {
            Storage::disk('public')->delete($productImage->image_path);
        }

        $productImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Imagen eliminada correctamente.'
        ]);
    }

    /**
     * Update sort order of images
     */
    public function updateSortOrder(Request $request){
        
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.sort_order' => 'required|integer'
        ]);

        foreach ($request->images as $imageData) {
            ProductImage::where('id', $imageData['id'])
                ->update(['sort_order' => $imageData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizado correctamente.'
        ]);
    }

    /**
     * Set featured image
     */
    public function setFeatured(ProductImage $productImage){

        // Quitar featured de todas las imágenes del producto
        ProductImage::where('product_id', $productImage->product_id)
            ->update(['is_featured' => false]);

        // Establecer esta imagen como featured
        $productImage->update(['is_featured' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Imagen destacada actualizada.'
        ]);
    }
}