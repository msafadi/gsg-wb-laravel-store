<?php

namespace App\Notifications;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     * Available channels: mail, database, broadcast, nexmo (sms), slack
     * 
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $order = $this->order;
        $billing = $order->addresses()->where('type', '=', 'billing')->first();

        $line1 = "{$billing->first_name} {$billing->last_name} has placed a new order (#{$order->number}) on your store";
        return (new MailMessage)
                    ->from('invoices@gazaskygeeks.com', 'GSG Billing Account')
                    ->subject('New Order #' . $this->order->number)
                    ->greeting("Hi $notifiable->name,")
                    ->line($line1)
                    ->action('View Order', url('/dashboard/orders/' . $order->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        $order = $this->order;
        $billing = $order->addresses()->where('type', '=', 'billing')->first();

        $line1 = "{$billing->first_name} {$billing->last_name} has placed a new order (#{$order->number}) on your store";
        
        return [
            // Basic Notification Data
            'title' => 'New Order #' . $this->order->number,
            'body' => $line1,
            'image' => '',
            'url' => url('/dashboard/orders/' . $order->id),
            // Custom Data
            'order' => $this->order,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $order = $this->order;
        $billing = $order->addresses()->where('type', '=', 'billing')->first();

        $line1 = "{$billing->first_name} {$billing->last_name} has placed a new order (#{$order->number}) on your store";
        
        return new BroadcastMessage([
            'title' => 'New Order #' . $this->order->number,
            'body' => $line1,
            'image' => '',
            'url' => url('/dashboard/orders/' . $order->id),
            'order' => $this->order,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
