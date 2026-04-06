<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserRegisterController extends Controller
{
    public function showRegister()
    {
        // If already logged in, go to dashboard
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard', ['userId' => Auth::guard('web')->id()]);
        }
        return view('auth.user.register');
    }

    public function register(Request $request)
    {
        // Validate all fields before doing anything
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed', // 'confirmed' checks password_confirmation field
            'phone'                 => 'nullable|string|max:20',
        ]);

        // Generate a random 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create user — NOT yet verified
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'phone'           => $request->phone,
            'otp'             => $otp,
            'otp_expires_at'  => now()->addMinutes(10), // OTP valid for 10 minutes
            'email_verified'  => false,
        ]);

        // Send OTP email
        // We use a simple Mail facade with a Mailable class
        Mail::to($user->email)->send(new \App\Mail\OtpMail($user, $otp));

        // Store user ID in session so OTP page knows who to verify
        session(['otp_user_id' => $user->id]);

        return redirect()->route('user.otp.show')
            ->with('success', 'We sent a 6-digit OTP to ' . $user->email);
    }
}