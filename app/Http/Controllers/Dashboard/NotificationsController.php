<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function read($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        return redirect()->to($notification->data['url']);
    }
}
