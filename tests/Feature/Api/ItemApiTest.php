<?php

use App\Models\City;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Province;
use Laravel\Sanctum\Sanctum;

describe('Get Items', function () {
    it('can retrieve items with pagination', function () {
        Sanctum::actingAs(User::factory()->create());
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);
        Item::factory()->count(5)->create();

        $response = $this->getJson('/api/item?limit=3');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'category', 'address', 'city', 'province', 'images', 'user', 'isFound', 'foundAt', 'isActive', 'createdAt', 'countComment']
                ],
                'nextCursor',
            ]);
    });

    it('returns 404 if no items exist', function () {
        Sanctum::actingAs(User::factory()->create());

        Item::query()->delete();

        $response = $this->getJson('/api/item');

        $response->assertStatus(404);
    });
});

describe('Create Item', function () {
    it('can create an item', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $category = Category::factory()->create();
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);

        $payload = [
            'userId' => $user->id,
            'name' => 'Lost Phone',
            'description' => 'A black phone found on the street',
            'address' => 'Jl. Kebon Jeruk',
            'categoryId' => $category->id,
            'cityId' => $city->id,
            'provinceId' => $province->id,
        ];

        $response = $this->postJson('/api/item', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Lost Phone'])
            ->assertJsonStructure(['data' => ['id', 'name', 'description']]);

        expect(Item::where('name', 'Lost Phone')->exists())->toBeTrue();
    });

    it('returns validation errors if request is invalid', function () {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/item', []); // empty payload

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['userId', 'name', 'description', 'address', 'categoryId', 'cityId', 'provinceId']);
    });

    it('requires authentication to create an item', function () {
        $response = $this->postJson('/api/item', [
            'name' => 'Unauthorized',
            'description' => '...',
            'address' => '...',
            'userId' => 'invalid',
            'categoryId' => 'invalid',
            'cityId' => 'invalid',
            'provinceId' => 'invalid',
        ]);

        $response->assertStatus(401);
    });
});
