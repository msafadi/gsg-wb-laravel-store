<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DatabaseRepository implements CartRepository
{
    protected $items;

    protected $cookie_id;

    public function __construct($cookie_id)
    {
        $this->cookie_id = $cookie_id;
    }

    public function all()
    {
        $id = Auth::id();
        $this->items = Cart::with('product')
            ->where('cookie_id', '=', $this->cookie_id)
            ->when($id, function($query, $id) {
                $query->where('user_id', $id);
            })
            ->get();

        return $this->items;
    }

    public function add($item, $qty = 1)
    {
        $cookie_id = $this->cookie_id;

        $cart = Cart::where([
            'cookie_id' => $cookie_id,
            'product_id' => $item,
        ])->first();

        if (!$cart) {
            Cart::create([
                'id' => Str::uuid(),
                'cookie_id' => $cookie_id,
                'user_id' => Auth::id(),
                'product_id' => $item,
                'quantity' => $qty,
            ]);
        } else {
            $cart->increment('quantity', $qty);
        }
    }

    public function remove($id)
    {
        Cart::where([
            'id' => $id,
            'cookie_id' => $this->cookie_id,
        ])->delete();
    }

    public function empty()
    {
        Cart::where([
            'cookie_id' => $this->cookie_id,
        ])->delete();
    }

    public function total()
    {
        return $this->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
    }
}