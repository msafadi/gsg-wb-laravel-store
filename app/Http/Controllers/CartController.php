<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    // List of cart products (items)
    public function index()
    {

    }

    // Add product to cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'int', 'exists:products,id'],
            'quantity' => ['int', 'min:1'],
        ]);

        // app()->make('cart.cookie_id')
        // App::make('cart.cookie_id')
        $cookie_id = app('cart.cookie_id');

        $cart = Cart::where([
            'cookie_id' => $cookie_id,
            'product_id' => $request->post('product_id'),
        ])->first();

        if (!$cart) {
            Cart::create([
                'id' => Str::uuid(),
                'cookie_id' => $cookie_id,
                'user_id' => Auth::id(),
                'product_id' => $request->post('product_id'),
                'quantity' => $request->post('quantity', 1),
            ]);
        } else {
            $cart->increment('quantity', $request->post('quantity', 1));
        }

        return redirect()->back()->with('success', 'Product added to cart');
    }
}
