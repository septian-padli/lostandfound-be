<?php

use App\Models\City;
use App\Models\User;
use App\Models\Province;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\patchJson;

describe('get user', function () {
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
});

describe('update user', function () {
    it('can update user information', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        // simulate city and province
        $province = Province::factory()->create([
            'id' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            'name' => 'Jawa Barat',
            'slug' => 'jawa-barat',
        ]);
        $city = City::factory()->create([
            'name' => 'Bandung',
            'slug' => 'bandung',
            'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D', // Jawa Barat
        ]);

        $payload = [
            "username" => "new_username",
            "name" => "New Name",
            "photoprofile" => "https://example.com/photo.jpg",
            "city" => $city->id,
            "province" => $city->id_province
        ];

        $response = patchJson("/api/user/{$user->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'new_username',
                'name' => 'New Name',
                'photoprofile' => 'https://example.com/photo.jpg',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
            ]);

        expect($user->fresh()->username)->toBe('new_username');
    });
});
