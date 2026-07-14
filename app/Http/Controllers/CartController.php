<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\ShippingAddress;
use App\Models\Wishlist;

class CartController extends Controller
{

    public function index(){
        return view('cart.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function checkout()
    {
        $user = auth()->user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        $addresses = ShippingAddress::where('user_id', $user->id)->get();

        return view('cart.checkout', compact('cartItems', 'addresses'));
    }

    public function syncCartWishlist(Request $request)
    {
        $userId = auth()->id();

        // Sincronizar carrito
        foreach ($request->cart as $item) {
            \App\Models\Cart::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $item['id']],
                [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'sub_total' => $item['quantity'] * $item['price']
                ]
            );
        }

        // Sincronizar wishlist
        foreach ($request->wishlist as $item) {
            \App\Models\Wishlist::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $item['id']]
            );
        }

        // Obtener carrito actualizado desde BD
        $cartItems = \App\Models\Cart::where('user_id', $userId)->with('product')->get()->map(function ($item) {
            return [
                'id' => $item->product_id,
                'image' => asset('storage/' . $item->product->image),
                'url' => route('product.details', ['id' => $item->product_id, 'slug' => $item->product->slug]),
                'price' => $item->price,
                'name' => $item->product->name,
                'quantity' => $item->quantity
            ];
        });

        // Obtener wishlist actualizada desde BD
        $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)->with('product')->get()->map(function ($item) {
            return [
                'id' => $item->product_id,
                'image' => asset('storage/' . $item->product->image),
                'url' => route('product.details', ['id' => $item->product_id, 'slug' => $item->product->slug]),
                'price' => $item->product->price,
                'name' => $item->product->name
            ];
        });

        return response()->json([
            'success' => true,
            'cart' => $cartItems,
            'wishlist' => $wishlistItems
        ]);
    }

    public function remove($product_id){
        $userId = auth()->id();

        $deleted = \App\Models\Cart::where('user_id', $userId)
            ->where('product_id', $product_id)
            ->delete();

        return response()->json(['success' => $deleted > 0]);
    }
}
