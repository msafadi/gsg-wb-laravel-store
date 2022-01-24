<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $newProducts = Product::latest()->limit(8)->get();
        $topSales = Product::inRandomOrder()->limit(6)->get();

        return view('store.home', [
            'newProducts' => $newProducts,
            'topSales' => $topSales,
        ]);
    }

}
