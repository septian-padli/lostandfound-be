<?php

use App\Models\City;
use App\Models\Item;
use App\Models\User;
use App\Models\Province;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('Upload Item Images', function () {

    beforeEach(function () {
        Storage::fake('public');
    });

    it('can upload multiple images for an item', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);
        $item = Item::factory()->create(['id_user' => $user->id]);

        $response = $this->postJson("/api/item/{$item->id}/images", [
            'images' => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.png'),
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'url',
                    ]
                ]
            ]);

        $this->assertTrue(
            Storage::disk('public')->exists('items/' . basename($response->json('data')[0]['url']))
        );
    });

    it('returns validation error when no images uploaded', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);
        $item = Item::factory()->create(['id_user' => $user->id]);

        $response = $this->postJson("/api/item/{$item->id}/images", [
            'images' => [],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['images']);
    });

    it('requires authentication', function () {
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);
        $item = Item::factory()->create();

        $response = $this->postJson("/api/item/{$item->id}/images", [
            'images' => [
                UploadedFile::fake()->image('photo.jpg'),
            ],
        ]);

        $response->assertStatus(401);
    });
});
