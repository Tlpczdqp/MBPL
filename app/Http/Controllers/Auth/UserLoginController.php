<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard', ['userId' => Auth::guard('web')->id()]);
        }
        return view('auth.user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember'); // remember me checkbox

        // attempt() tries to log in and creates a session if successful
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();

            // Block unverified users
            if (!$user->email_verified) {
                Auth::guard('web')->logout();
                // Put them back in OTP flow
                session(['otp_user_id' => $user->id]);
                return redirect()->route('user.otp.show')
                    ->with('error', 'Please verify your email first.');
            }

            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            return redirect()->route('user.dashboard', ['userId' => $user->id]);
        }

        // Login failed — return error (we don't say which field is wrong, for security)
        return back()->withErrors(['email' => 'These credentials do not match our records.'])
            ->withInput($request->only('email'));
    }
}