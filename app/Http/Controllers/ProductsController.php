<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    
    public function index(Category $category = null)
    {
        if (!$category) {
            $category = new Category;
            $products = Product::with('category')->latest()->paginate(15);
        } else {
            $products = $category->products()->with('category')->latest()->paginate();
        }
        
        return view('store.products.index', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function show(Category $category, Product $product)
    {
        // $category = Category::whereSlug($category_slug)->firstOrFail();
        // $product  = $category->products()->whereSlug($product_slug)->firstOrFail();

        return view('store.products.show', compact('category', 'product'));
    }
}
