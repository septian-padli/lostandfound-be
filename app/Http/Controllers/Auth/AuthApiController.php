<?php

namespace App\Http\Controllers\Auth;

use Google_Client;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthApiController extends Controller
{
    public function __construct(private Google_Client $googleClient) {}

    public function login(Request $request)
    {
        $request->validate(['id_token' => 'required|string']);

        $payload = $this->googleClient->verifyIdToken($request->id_token);

        if (!$payload) {
            return response()->json(['error' => 'Invalid Google token'], 401);
        }

        $user = User::updateOrCreate(
            ['googleId' => $payload['sub']],
            ['email' => $payload['email'], 'name' => $payload['name']]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'token' => $token,
            ]
        ]);
    }
}
