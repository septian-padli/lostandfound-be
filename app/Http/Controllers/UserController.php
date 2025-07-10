<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['city', 'province'])
            ->where(function ($query) use ($id) {
                $query->where('id', $id)
                    ->orWhere('email', $id)
                    ->orWhere('username', $id)
                    ->orWhere('token', $id)
                    ->orWhere('googleId', $id);
            })
            ->first();


        if (!$user) {
            return response()->json([
                'errors' => ['message' => 'User not found'],
            ], 404);
        }

        // return $user;
        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idOrEmail)
    {
        $user = User::query()
            ->where('id', $idOrEmail)
            ->orWhere('email', $idOrEmail)
            ->first();

        if (!$user) {
            return response()->json([
                'errors' => ['message' => 'User not found'],
            ], 404);
        }

        $validated = $request->validate([
            'email' => 'sometimes|email|unique:users,email,' . $user->id . ',id',
            'googleId' => 'sometimes|unique:users,googleId,' . $user->id . ',id',
            'name' => 'sometimes|string|nullable',
            'username' => 'sometimes|string|nullable|unique:users,username,' . $user->id . ',id',
            'photoprofile' => 'sometimes|string|nullable',
            'city' => 'sometimes|nullable|exists:cities,id',
            'province' => 'sometimes|nullable|exists:provinces,id',
            'isAdmin' => 'sometimes|boolean',
            'email_verified_at' => 'sometimes|nullable|date',
            'password' => 'sometimes|string|nullable|min:6',
        ]);

        if (isset($validated['city'])) {
            $validated['id_city'] = $validated['city'];
            unset($validated['city']);
        }
        if (isset($validated['province'])) {
            $validated['id_province'] = $validated['province'];
            unset($validated['province']);
        }

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->fill($validated);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
