<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationsMenu extends Component
{
    public $notifications;

    public $unread;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($count = 5)
    {
        $user = Auth::user();
        $this->notifications = $user->notifications()->limit($count)->get();
        $this->unread = $user->unreadNotifications()->count();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.notifications-menu');
    }
}
