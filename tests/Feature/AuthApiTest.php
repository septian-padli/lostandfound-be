<?php

use Mockery;
use Google_Client;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;

it('can login using mocked Google_Client', function () {
    // Mock Google_Client
    $mockGoogleClient = Mockery::mock(Google_Client::class);
    $mockGoogleClient->shouldReceive('verifyIdToken')
        ->once()
        ->andReturn([
            'sub' => '1234567890',
            'email' => 'mockuser@example.com',
            'name' => 'Mocked User',
            'picture' => 'https://example.com/avatar.jpg',
        ]);

    // Replace Google_Client di Laravel container dengan mock
    $this->app->instance(Google_Client::class, $mockGoogleClient);

    // Call API
    $response = $this->postJson('/api/login', [
        'id_token' => 'any-token',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['id', 'email', 'name', 'token'],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'mockuser@example.com',
    ]);
});
