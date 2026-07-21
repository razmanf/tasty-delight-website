<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMailable;

class RegistrationOtpController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'unique:users'],
            'contact_number' => ['required', 'string', 'size:10', 'regex:/^0[0-9]{9}$/', 'unique:users'],
        ]);

        $contactNumber = $request->contact_number;
        $email = $request->email;

        // Generate a 6-digit code
        $code = random_int(100000, 999999);

        // Save to Cache for 10 minutes, using the phone number as the key
        Cache::put('registration_otp_' . $contactNumber, $code, now()->addMinutes(10));

        // Actually send the OTP via Email!
        Mail::to($email)->send(new OtpMailable($code));

        // Also log it for debugging
        error_log("REGISTRATION OTP for $email (Phone: $contactNumber): $code");

        return response()->json(['message' => 'OTP sent successfully']);
    }
}
