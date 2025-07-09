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

        // Make sure $user is loaded by integer id (primary key)
        $user = User::find($user->id);

        Auth::guard('web')->login($user);
        if (!$user->isAdmin) {
            return redirect('/');
        }

        // return redirect('/admin');
        $token = $user->createToken('auth_token')->plainTextToken;

        // Tampilkan token ke user (sementara, untuk testing)
        return response()->json([
            'message' => 'Login Success',
            'token' => $token,
            'user' => $user
        ]);
    }


    public function logout(Request $request)
    {
        // function auth logout
        Auth::guard('web')->logout();
        $request->session()->invalidate();  // Invalidasi session lama
        $request->session()->regenerateToken();  // Buat CSRF token baru (opsional)

        return redirect('/')->with('status', 'Logout berhasil');
    }
}
