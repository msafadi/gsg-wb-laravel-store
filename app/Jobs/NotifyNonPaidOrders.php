<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\NonPaidOrderReminderNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class NotifyNonPaidOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::where('payment_status', '<>', 'paid')
            ->join('order_addresses', function($join) {
                $join->on('order_addresses.order_id', '=', 'orders.id')
                     ->where('order_addresses.type', '=', 'billing');
            })
            ->whereDate('created_at', '<=', Carbon::now()->subDays(7))
            ->get();

        foreach ($orders as $order) {
            Notification::route('mail', $order->email)
                ->notify(new NonPaidOrderReminderNotification);
        }
    }
}
