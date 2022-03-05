<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Cart\CartRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(CartRepository $cart)
    {
        if ($cart->all()->count() == 0) {
            return redirect()->route('home');
        }
        $user = Auth::check()? Auth::user() : new User();

        return view('store.checkout', compact('cart', 'user'));
    }

    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
            'shipping.first_name' => ['required'],
            'shipping.last_name' => ['required'],
            'shipping.street' => ['required'],
            'shipping.city' => ['required'],
            'shipping.country_code' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            // 1: Create Order and Items
            $order = $this->storeOrder($request, $cart);
            // 2: Add Order Addresses
            $this->storeAddresses($order, $request);
            // 3: Empty Cart
            $cart->empty();
            // 4: Commit
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // event('order.created', [$cart, Auth::user()]);
        event( new OrderCreated($order) );

        return redirect()->route('payments.create', $order->id);
    }

    protected function storeOrder(Request $request, CartRepository $cart)
    {
        $order = Order::create([
            'user_id' => Auth::id(),
            'tax' => $request->post('tax', 0),
            'discount' => $request->post('discount', 0),
            'total' => $cart->total(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->storeOrderItems($order, $cart);

        return $order;
    }

    protected function storeAddresses(Order $order, Request $request)
    {
        $shipping_addr = $request->input('shipping');
        $shipping_addr['type'] = 'shipping';
        $order->addresses()->create($shipping_addr);

        $billing_addr = $request->input('billing');
        if (!$billing_addr) {
            $billing_addr = $shipping_addr;
        }
        $billing_addr['type'] = 'billing';
        $order->addresses()->create($billing_addr);
    }

    protected function storeOrderItems(Order $order, CartRepository $cart)
    {
        foreach ($cart->all() as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ]);
        }
    }
}
