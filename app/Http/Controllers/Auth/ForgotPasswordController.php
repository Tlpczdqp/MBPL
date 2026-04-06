<?php
// This file MUST exist at app/Http/Controllers/Auth/ForgotPasswordController.php
// The folder Auth/ must exist inside Controllers/
// Create the folder if it doesn't exist

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    // ── SHOW FORGOT PASSWORD FORM ────────────────────────────────
    // This is the page where the user types their email
    public function showForgotForm()
    {
        // If already logged in, no need to reset password
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard', ['userId' => Auth::guard('web')->id()]);
        }

        return view('auth.user.forgot-password');
    }

    // ── SEND RESET LINK ──────────────────────────────────────────
    // Called when the user submits their email on the forgot password form
    public function sendResetLink(Request $request)
    {
        // Validate the email field
        $request->validate([
            'email' => 'required|email',
        ]);

        // Find the user with this email
        $user = User::where('email', $request->email)->first();

        // IMPORTANT SECURITY NOTE:
        // We always return the same success message whether the email exists or not
        // This prevents attackers from knowing which emails are registered
        // (called "email enumeration protection")
        if (!$user) {
            // Pretend we sent the email even if user doesn't exist
            return back()->with(
                'status',
                'If that email exists in our system, a reset link has been sent.'
            );
        }

        // Generate a secure random token
        // This token is used to verify the reset link is valid
        $token = Str::random(64);

        // Delete any existing reset tokens for this email
        // We only want ONE active reset token at a time per user
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Store the new token in the database
        // We hash the token so even if the database is leaked,
        // attackers cannot use the raw token
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),  // hashed for security
            'created_at' => Carbon::now(),
        ]);

        // Build the reset URL with the raw token (not hashed)
        // The raw token is what goes in the URL
        // We verify it against the hashed version stored in DB
        $resetUrl = route('user.password.reset', [
            'token' => $token,
            'email' => $request->email, // pre-fill the email on the reset form
        ]);

        // Send the email using our PasswordResetMail Mailable
        Mail::to($user->email)->send(
            new \App\Mail\PasswordResetMail($user, $resetUrl)
        );

        return back()->with(
            'status',
            'If that email exists in our system, a reset link has been sent. Check your inbox.'
        );
    }

    // ── SHOW RESET PASSWORD FORM ─────────────────────────────────
    // The user clicks the link in their email and lands here
    // The URL contains the token and email as query parameters
    public function showResetForm(Request $request, string $token)
    {
        // Pass the token and email to the view
        // The view will put them in hidden fields in the form
        return view('auth.user.reset-password', [
            'token' => $token,
            'email' => $request->query('email'), // read ?email= from URL
        ]);
    }

    // ── RESET THE PASSWORD ───────────────────────────────────────
    // Called when the user submits their new password
    public function resetPassword(Request $request)
    {
        // Validate all fields
        $request->validate([
            'token'                 => 'required|string',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        // Find the reset record in the database by email
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Check if a record exists for this email
        if (!$resetRecord) {
            return back()->withErrors([
                'email' => 'No password reset request found for this email.',
            ]);
        }

        // Check if the token has expired (60 minutes limit)
        $tokenAge = Carbon::parse($resetRecord->created_at);
        if (Carbon::now()->diffInMinutes($tokenAge) > 60) {
            // Delete the expired token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return back()->withErrors([
                'email' => 'The password reset link has expired. Please request a new one.',
            ])->withInput(['email' => $request->email]);
        }

        // Verify the token from the URL against the hashed token in DB
        // Hash::check($plain, $hashed) → true if they match
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors([
                'email' => 'This password reset link is invalid.',
            ]);
        }

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Update the user's password
        // Hash::make() hashes the new password securely
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the used token — it can only be used ONCE
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Redirect to login with success message
        return redirect()
            ->route('user.login')
            ->with('success', 'Password reset successfully! You can now log in with your new password.');
    }
}