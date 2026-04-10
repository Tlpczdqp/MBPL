<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // GOOGLE
    public function redirectToGoogle()
    {
        // Redirect user to Google's OAuth page
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Get the user data Google sends back
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            //test
        //     dd([
        //     'error'   => $e->getMessage(),
        //     'file'    => $e->getFile(),
        //     'line'    => $e->getLine(),
        // ]);
        

            return redirect()->route('user.login')
                ->with('error', 'Google login failed. Please try again.');
        }

        // Find existing user by google_id OR email
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            // Update google_id if they logged in with email before
            $user->update(['google_id' => $googleUser->getId()]);
        } else {
            $user = User::create([
                'name'            => $googleUser->getName(),
                'email'           => $googleUser->getEmail(),
                'google_id'       => $googleUser->getId(),
                'avatar'          => $googleUser->getAvatar(),
                'email_verified'  => true,          
                'email_verified_at' => now(),
            ]);
        }

        Auth::guard('web')->login($user, true); // true = remember me

        return redirect()->route('user.dashboard', ['userId' => $user->id]);
    }

    // FACEBOOK 
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $fbUser = Socialite::driver('facebook')->stateless()->user();
        } catch (\Exception $e) {
        //     dd([
        //     'error_class' => get_class($e),
        //     'message'     => $e->getMessage(),
        //     'file'        => $e->getFile(),
        //     'line'        => $e->getLine(),
        // ]);
            return redirect()->route('user.login')
                ->with('error', 'Facebook login failed. Please try again.');
        }

        $user = User::where('facebook_id', $fbUser->getId())
                    ->orWhere('email', $fbUser->getEmail())
                    ->first();

        if ($user) {
            $user->update(['facebook_id' => $fbUser->getId()]);
        } else {
            $user = User::create([
                'name'            => $fbUser->getName(),
                'email'           => $fbUser->getEmail(),
                'facebook_id'     => $fbUser->getId(),
                'avatar'          => $fbUser->getAvatar(),
                'email_verified'  => true,
                'email_verified_at' => now(),
            ]);
        }

        Auth::guard('web')->login($user, true);
        return redirect()->route('user.dashboard', ['userId' => $user->id]);
    }
}