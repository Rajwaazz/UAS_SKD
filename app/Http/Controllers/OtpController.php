<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function showOTP()
    {
        return view('showotp');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $registerData = $request->session()->get('register_data');
        if (!$registerData)
        {
            return redirect()->route('auth.register')->with('error', 'Sesi telah habis');
        }

        $otpRecord = Otp::validOTP($registerData('email'), $request->otp)->first();

        if(!$otpRecord)
        {
            return back()->withErrors('error', 'Invalid OTP Or Session ended');
        }

        $user = User::create([
            'name' => $registerData['name'],
            'email' => $registerData['email'],
            'password' => bcrypt($registerData['password']),
            'role' => 'user'
        ]);

        $otpRecord->update(['verified' => true]);
        $request->session()->forget('register_data');

        return redirect()->route('auth.login')->with('success', 'Registrasion Succesfully');
    }

    public function sendOTPEmail($email, $otp)
    {
        Mail::raw("Kode OTP Anda Adalah: $otp", function($message) use ($email) {
            $message->to($email)->subject('Kode verifikasi OTP');
        });
    }
}
