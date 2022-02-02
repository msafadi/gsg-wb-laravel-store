<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{

    // List of cart products (items)
    public function index(CartRepository $cart)
    {
        return view('store.cart', [
            'cart' => $cart
        ]);
    }

    // Add product to cart
    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
            'product_id' => ['required', 'int', 'exists:products,id'],
            'quantity' => ['int', 'min:1'],
        ]);

        $cart->add($request->post('product_id'), $request->post('quantity'));

        return redirect()->back()->with('success', 'Product added to cart');
    }

    public function destroy(CartRepository $cart, $id)
    {
        $cart->remove($id);
        return redirect()->back()
            ->with('success', 'Item removed from the cart');
    }
}
