<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('user.profile.index', [
            'user' => $user,
        ]);
    }

    public function updateInfo(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(),
            ],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->with('tab', 'password');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()
            ->with('success', 'Password changed successfully.')
            ->with('tab', 'password');
    }

    /**
     * Soft delete the authenticated user's account.
     * Sets deleted_at timestamp — data is retained in the database.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_password' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Verify password before soft deleting
        if (! Hash::check($request->confirm_password, $user->password)) {
            return back()
                ->withErrors(['confirm_password' => 'The password you entered is incorrect.'])
                ->with('tab', 'delete');
        }

        // Log the user out first
        Auth::logout();

        // Soft delete — sets deleted_at, does NOT remove from DB
        $user->delete();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/user/login')->with('success', 'Your account has been deactivated. Contact support to reactivate.');
    }
}