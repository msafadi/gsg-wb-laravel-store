<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokensController extends Controller
{
    /**
     * Authenticate user and return access token (login)
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['nullable'],
            'scopes' => ['sometimes', 'string']
        ]);

        $user = User::where('email', '=', $request->email)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $name = $request->post('device_name', $request->userAgent());
            // scopes: categories.update,categoires.create,profile.update
            if ($request->has('scopes')) {
                $abilities = explode(',', $request->post('scopes'));
            } else {
                $abilities = ['*'];
            }

            $token = $user->createToken($name, $abilities);

            return Response::json([
                'token' => $token->plainTextToken,
                'user' => $user,
            ], 201);

        }

        // 401
        return Response::json([
            'message' => __('Invalid credentials!')
        ], 401);
    }

    public function destroy($token = null)
    {
        $user = Auth::guard('sanctum')->user();

        // Revoke a specified token!
        if ($token) {
            if ($token == 'all') {
                // Logout out form all devices
                $user->tokens()->delete();
                return;
            }

            // $user->tokens()
            //     ->where('token', '=',  hash('sha256', $token))
            //     ->delete();

            $token = PersonalAccessToken::findToken($token);
            if ($token->tokenable_id != $user->id || $token->tokenable_type != get_class($user)) {
                abort(403);
            }

            $token->delete();
            return;
        }

        // Logout from current device
        $user->currentAccessToken()->delete();

    }
}
