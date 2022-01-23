<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangeUserPasswordController extends Controller
{
    public function index()
    {
        return view('auth.change-password');
    }
    
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'password' => ['required', 'password'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => Hash::make($request->post('new_password')),
            'remember_token' => null,
        ])->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Your password changed.');
    }
}
