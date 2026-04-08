<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserOtpController extends Controller
{
    public function showOtp()
    {
        // If there's no pending OTP user in session, redirect back
        if (!session('otp_user_id')) {
            return redirect()->route('user.register');
        }
        return view('auth.user.otp-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = session('otp_user_id');
        $user   = User::findOrFail($userId);

        // Check if OTP matches and hasn't expired
        if (!$user->isOtpValid($request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        // OTP is valid! Mark email as verified and clear OTP
        $user->update([
            'email_verified'    => true,
            'email_verified_at' => now(),
            'otp'               => null,         // clear OTP so it can't be reused
            'otp_expires_at'    => null,
        ]);

        // Clear session
        session()->forget('otp_user_id');

        // Log the user in
        Auth::guard('web')->login($user);

        return redirect()->route('user.dashboard', ['userId' => $user->id])
            ->with('success', 'Email verified! Welcome, ' . $user->name);
    }

    public function resendOtp()
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('user.register');
        }

        $user = User::findOrFail($userId);
        
        // Generate new OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new \App\Mail\OtpMail($user, $otp));

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}