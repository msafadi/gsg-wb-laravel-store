<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendOtpMessage;
use App\Models\Otp;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;

class OtpController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => ['required']
        ]);

        $phone = $request->post('phone_number');
        $code = rand(111111, 999999);

        Otp::updateOrCreate([
            'phone_number' => $phone,
        ], [
            'code' => Hash::make($code),
            'created_at' => Carbon::now(),
        ]);

        // dispatch(new SendOtpMessage($phone, $code));
        // SendOtpMessage::dispatch($phone, $code);
            
        return [
            'message' => __('OTP sent.'),
            'code' => $code,
        ];
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone_number' => ['required'],
            'code' => ['required', 'digits:6'],
        ]);

        $otp = Otp::findOrFail($request->post('phone_number'));

        if ( !Hash::check($request->post('code'), $otp->code) ) {
            return Response::json([
                'message' => __('Invalid otp code'),
            ], 422);
        }

        $otp->delete();

        // Create user, change password, created access token

        return Response::json([
            'message' => __('Code verified'),
        ]);
    }
}
