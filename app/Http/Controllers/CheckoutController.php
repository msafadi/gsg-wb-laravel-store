<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(CartRepository $cart)
    {
        return view('store.checkout', compact('cart'));
    }

    public function store(Request $request, CartRepository $cart)
    {
        // event('order.created', [$cart, Auth::user()]);
        event( new OrderCreated($cart->total()) );
    }
}
