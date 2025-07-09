<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $provider */
        $provider = Socialite::driver('google');
        $googleUser = $provider->stateless()->user();


        $user = User::updateOrCreate(
            ['googleId' => $googleUser->getId()],
            [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'username' => strstr($googleUser->getEmail(), '@', true),
                'photoprofile' => $googleUser->getAvatar()
            ]
        );

        Auth::login($user);
        if (!$user->isAdmin) {
            return redirect('/')->with('error', 'Unauthorized');
        }

        return redirect('/admin');
    }


    public function logout(Request $request)
    {
        Auth::logout();  // Logout user dari session
        $request->session()->invalidate();  // Invalidasi session lama
        $request->session()->regenerateToken();  // Buat CSRF token baru (opsional)

        return redirect('/')->with('status', 'Logout berhasil');
    }
}
