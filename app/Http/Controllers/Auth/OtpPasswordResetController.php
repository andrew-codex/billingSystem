<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;

class OtpPasswordResetController extends Controller
{
  
public function requestOtp(Request $request)
{
    // Get email from input or session
    $email = $request->input('email') ?? session('reset_email');

    if (!$email) {
        return back()->withErrors(['email' => 'Email is required for OTP!']);
    }

    $request->merge(['email' => $email]); 

    $request->validate([
        'email' => 'required|email|exists:users,email',
    ], [
        'email.exists' => 'No account found with that email.',
    ]);

    $key = 'otp:' . $request->ip();
    if (RateLimiter::tooManyAttempts($key, 3)) {
        return back()->withErrors(['email' => 'Too many OTP requests. Try again in 1 minute.']);
    }

    RateLimiter::hit($key, 60);

    $otp = random_int(100000, 999999);
    $expiresAt = now()->addMinutes(5);

    PasswordOtp::updateOrCreate(
        ['email' => $email],
        ['otp' => $otp, 'expires_at' => $expiresAt]
    );

    Mail::to($email)->send(new SendOtpMail($otp));

    session(['reset_email' => $email]); // update session

    return redirect()->route('password.reset.form')->with([
        'success' => 'OTP sent successfully! Please check your email.',
        'otp_expires' => $expiresAt->toDateTimeString(),
    ]);
}


    public function resendOtp()
    {
        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request.form')->withErrors([
                'email' => 'Please enter your email first.'
            ]);
        }

        $otp = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        PasswordOtp::updateOrCreate(
            ['email' => $email],
            ['otp' => $otp, 'expires_at' => $expiresAt]
        );

        Mail::to($email)->send(new SendOtpMail($otp));

        return back()->with([
            'success' => 'A new OTP has been sent to your email.',
            'otp_expires' => $expiresAt->toDateTimeString(),
        ]);
    }


 
    public function resetPassword(Request $request)
    {
        $email = $request->email ?? session('reset_email');

        $request->merge(['email' => $email]);

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:6',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'confirmed'
            ],
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'email.exists' => 'No account found with that email.',
        ]);

        $otpRecord = PasswordOtp::where('email', $email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $user = User::where('email', $email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        $otpRecord->delete();
        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password reset successfully! You can now log in.');
    }

}
