<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    //function to redirect to provider
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    // function to handle callback
    public function handleProviderCallback($provider)
    {
        // get user data from provider
        $socialUser = Socialite::driver($provider)->user();
        // check if user exists
        $user = User::where('email', $socialUser->getEmail())->first();
        if(!$user){
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
            ]);
        }
        // login user
        Auth::login($user);

        return redirect()->route('dashboard');
        
    }
}
