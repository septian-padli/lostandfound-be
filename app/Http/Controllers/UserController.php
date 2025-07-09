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
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
