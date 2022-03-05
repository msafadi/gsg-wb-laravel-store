<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PayPalHttp\HttpException;
use Illuminate\Support\Facades\App;
use App\Providers\RouteServiceProvider;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;

class PaymentsController extends Controller
{
    public function create(Order $order)
    {
        if ($order->payment_status == 'paid') {
            return redirect(RouteServiceProvider::HOME);
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "reference_id" => $order->id,
                    "amount" => [
                        "value" => $order->total,
                        "currency_code" => "ILS",
                    ],
                ],
            ],
            "application_context" => [
                "cancel_url" => route('payments.cancel', $order->id),
                "return_url" => route('payments.callback', $order->id),
            ],
        ];

        try {
            $client = App::make('paypal.client');
            
            // Call API with your client and get a response for your call
            $response = $client->execute($request);

            if ($response->statusCode == 201) {
                foreach ($response->result->links as $link) {
                    if ($link->rel == 'approve') {
                        return redirect()->away($link->href);
                    }
                }
            }
            // error!!
        } catch (HttpException $ex) {
            echo $ex->statusCode;
            dd($ex->getMessage());
        }
    }

    public function callback(Request $request, Order $order)
    {
        if ($order->payment_status == 'paid') {
            return redirect(RouteServiceProvider::HOME);
        }

        $paypalOrderId = $request->query('token');
        $captureRequest = new OrdersCaptureRequest( $paypalOrderId );
        $captureRequest->prefer('return=representation');
        try {
            $client = App::make('paypal.client');
            // Call API with your client and get a response for your call
            $response = $client->execute($captureRequest);
            
            if ($response->statusCode == 201 && $response->result->status == 'COMPLETED') {
                $order->forceFill([
                    'payment_status' => 'paid',
                    'payment_method' => 'paypal',
                    'payment_transaction_id' => $paypalOrderId,
                    'payment_data' => $response,
                ])->save();

                return redirect(RouteServiceProvider::HOME);
            }

        } catch (HttpException $ex) {
            echo $ex->statusCode;
            dd($ex->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        
    }

    public function refund(Order $order)
    {
        if ($order->payment_status != 'paid') {
            return redirect(RouteServiceProvider::HOME);
        }

        $captureId = $order->payment_data['result']['purchase_units'][0]['payments']['captures'][0]['id'];

        $request = new CapturesRefundRequest($captureId);
        $request->body = [
            'amount' =>
                [
                    'value' => $order->total,
                    'currency_code' => 'ILS'
                ]
            ];
        $client = App::make('paypal.client');
        $response = $client->execute($request);
        
        if ($response->statusCode == 201) {
            $order->status = 'refunded';
            $order->save();
        }
    }
}
