<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    public function index() {
        
        $topSellingProducts = Product::where('type', 'PRODUCTO')  // Filtro para solo PRODUCTO
        ->withCount('orderItems') 
        ->orderByDesc('order_items_count') 
        ->take(12) 
        ->get();

        return view('welcome', compact('topSellingProducts'));
    }
}
