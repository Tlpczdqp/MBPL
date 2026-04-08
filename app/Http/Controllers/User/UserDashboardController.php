<?php
// File location: app/Http/Controllers/User/DashboardController.php
// The folder User/ must exist inside Controllers/
// If it does not exist, create it manually or Laravel will error

namespace App\Http\Controllers\User;

// This tells PHP this class lives inside the User sub-folder of Controllers
// The namespace MUST match the folder path exactly

use App\Http\Controllers\Controller;
use App\Models\BusinessApplication;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    // ── DASHBOARD ────────────────────────────────────────────────
    // This is the main page the user sees after logging in
    // It shows a summary of their applications
    public function index($userId)
    {
        // Security check: the userId in the URL must match the logged-in user
        // This stops user A from visiting user B's dashboard
        abort_if(Auth::id() != $userId, 403, 'Unauthorized access.');

        $user = Auth::user();

        // Count applications by status for the summary cards
        // These numbers show on the dashboard as stats
        $stats = [
            'total'    => BusinessApplication::where('user_id', $userId)->count(),
            'pending'  => BusinessApplication::where('user_id', $userId)
                            ->where('status', 'pending')
                            ->count(),
            'approved' => BusinessApplication::where('user_id', $userId)
                            ->whereIn('status', ['approved', 'paid', 'permit_issued'])
                            ->count(),
            'rejected' => BusinessApplication::where('user_id', $userId)
                            ->where('status', 'rejected')
                            ->count(),
            'issued'   => BusinessApplication::where('user_id', $userId)
                            ->where('status', 'permit_issued')
                            ->count(),
        ];

        // Get the 5 most recent applications for the dashboard table
        // We use latest() to sort newest first
        $recentApplications = BusinessApplication::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Get unread notifications for the dashboard alert area
        $unreadNotifications = Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', $userId)
            ->where('is_read', false)
            ->latest()
            ->take(3)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'stats',
            'recentApplications',
            'unreadNotifications'
        ));
    }

    // ── PROFILE ──────────────────────────────────────────────────
    // This is the page the user sees when they click "My Profile"
    // They can view and update their personal information
    public function profile($userId)
    {
        // Security check
        abort_if(Auth::id() != $userId, 403, 'Unauthorized access.');

        $user = User::findOrFail($userId);

        // Count their total applications for the profile summary
        $applicationCount = BusinessApplication::where('user_id', $userId)->count();

        return view('user.profile', compact('user', 'applicationCount'));
    }

    // ── UPDATE PROFILE ───────────────────────────────────────────
    // Called when the user submits the profile edit form
    public function updateProfile(Request $request, $userId)
    {
        abort_if(Auth::id() != $userId, 403, 'Unauthorized access.');

        $user = User::findOrFail($userId);

        // Validate the form fields
        // email unique rule ignores the current user's own email
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20',
        ]);

        // Update basic info
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()
            ->route('user.profile', ['userId' => $userId])
            ->with('success', 'Profile updated successfully.');
    }

    // ── UPDATE PASSWORD ──────────────────────────────────────────
    // Separate form submission for changing password
    public function updatePassword(Request $request, $userId)
    {
        abort_if(Auth::id() != $userId, 403, 'Unauthorized access.');

        $user = User::findOrFail($userId);

        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        // Check if the current password they entered is correct
        // Hash::check($plain, $hashed) returns true if they match
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        // Make sure the new password is different from the old one
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Your new password must be different from your current password.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('user.profile', ['userId' => $userId])
            ->with('success', 'Password changed successfully.');
    }
}