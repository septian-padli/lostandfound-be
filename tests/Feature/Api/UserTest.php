<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can get user by id', function () {
    // Arrange
    $user = User::factory()->create();

    // Act as user (generate token session-based Sanctum)
    Sanctum::actingAs($user);

    // Act
    $response = $this->getJson("/api/user/{$user->id}");

    // Assert
    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'username' => $user->username,
                'photoprofile' => $user->photoprofile,
                'isAdmin' => $user->isAdmin,
                'city' => $user->city?->name,
                'province' => $user->province?->name,
            ],
        ]);
});

it('can get user by email', function () {
    // Arrange
    $user = User::factory()->create();

    // Act as user (generate token session-based Sanctum)
    Sanctum::actingAs($user);

    // Act
    $response = $this->getJson("/api/user/{$user->email}");

    // Assert
    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'username' => $user->username,
                'photoprofile' => $user->photoprofile,
                'isAdmin' => $user->isAdmin,
                'city' => $user->city?->name,
                'province' => $user->province?->name,
            ],
        ]);
});

it('returns 404 if user not found', function () {
    // Arrange
    $user = User::factory()->create();

    // Act as user (generate token session-based Sanctum)
    Sanctum::actingAs($user);

    // Act
    $response = $this->getJson("/api/user/notfound@example.com");

    // Assert
    $response->assertStatus(404)
        ->assertJsonStructure(['errors']);
});
