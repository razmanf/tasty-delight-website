<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RegistrationOtpController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contact_number' => ['required', 'string', 'size:10', 'regex:/^0[0-9]{9}$/', 'unique:users'],
        ]);

        $contactNumber = $request->contact_number;

        // Generate a 6-digit code
        $code = random_int(100000, 999999);

        // Save to Cache for 10 minutes, using the phone number as the key
        Cache::put('registration_otp_' . $contactNumber, $code, now()->addMinutes(10));

        // Simulate sending SMS via log (prints to php artisan serve terminal)
        error_log("===========================================");
        error_log("REGISTRATION SMS OTP for " . $contactNumber . ": " . $code);
        error_log("===========================================");

        return response()->json(['message' => 'OTP sent successfully']);
    }
}
