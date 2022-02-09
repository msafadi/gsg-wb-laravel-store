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

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query()
    {
        $id = Auth::id();
        $query = Cart::with('product');
        if ($id) {
            $query->where('user_id', '=', $id);
        } else {
            $query->where('cookie_id', '=', $this->cookie_id);
        }
        return $query;
    }

    public function all()
    {
        if ($this->items === null) {
            $this->items = $this->query()->get();
        }

        return $this->items;
    }

    public function add($item, $qty = 1)
    {
        $cookie_id = $this->cookie_id;

        $cart = $this->query()->where('product_id', '=', $item)->first();

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
        $this->query()->where('id', '=', $id)->delete();
    }

    public function empty()
    {
        $this->query()->delete();
    }

    public function total()
    {
        return $this->all()->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function setUserId($id)
    {
        Cart::where('cookie_id', '=', $this->cookie_id)
            ->whereNull('user_id') // user_id IS NULL
            ->update([
                'user_id' => $id
            ]);
    }
}