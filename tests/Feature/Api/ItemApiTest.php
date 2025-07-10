<?php

use App\Models\City;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Province;
use Laravel\Sanctum\Sanctum;

describe('Get all items', function () {
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
        $category = Category::factory()->create(['slug' => 'category-slug']);
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['slug' => 'city-slug', 'id_province' => $province->id]);

        $payload = [
            'userId' => $user->id,
            'name' => 'Lost Phone',
            'slug' => 'lost-phone', // tambahkan slug
            'description' => 'A black phone found on the street',
            'address' => 'Jl. Kebon Jeruk',
            'categoryId' => $category->id,
            'cityId' => $city->id,
            'provinceId' => $province->id,
        ];

        $response = $this->postJson('/api/item', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Lost Phone',
                'slug' => 'lost-phone', // pastikan slug muncul di response
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',           // tambahkan ini
                    'description',
                    // tambahkan property lain sesuai dengan ItemResource
                ]
            ]);

        expect(Item::where('name', 'Lost Phone')->where('slug', 'lost-phone')->exists())->toBeTrue();
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

describe('Get single item', function () {

    it('can get single item by ID', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $province = Province::factory()->create();
        $city = City::factory()->create(['id_province' => $province->id]);
        $category = Category::factory()->create();

        $item = Item::factory()->create([
            'id_user' => $user->id,
            'id_category' => $category->id,
            'id_city' => $city->id,
            'id_province' => $province->id,
        ]);

        $response = $this->getJson("/api/item/{$item->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
            ]);
    });

    it('returns 404 if item not found', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/item/nonexistent");

        $response->assertStatus(404)
            ->assertJsonStructure(['errors']);
    });

    it('can update item partially', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $province = Province::factory()->create();
        $city = City::factory()->create(['id_province' => $province->id]);
        $category = Category::factory()->create();

        $item = Item::factory()->create([
            'id_user' => $user->id,
            'id_category' => $category->id,
            'id_city' => $city->id,
            'id_province' => $province->id,
        ]);

        $payload = [
            'description' => 'Updated Description',
            'address' => 'Updated Address',
        ];

        $response = $this->patchJson("/api/item/{$item->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $item->id,
                'description' => 'Updated Description',
                'address' => 'Updated Address',
            ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'description' => 'Updated Description',
            'address' => 'Updated Address',
        ]);
    });

    it('returns 404 when updating non-existing item', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'description' => 'Nothing',
        ];

        $response = $this->patchJson("/api/item/nonexistent", $payload);

        $response->assertStatus(404);
    });

    it('requires authentication for get and patch', function () {
        $itemId = '01hxvwnny6cykj0q1nxh6g8sd9';

        $this->getJson("/api/item/{$itemId}")->assertStatus(401);
        $this->patchJson("/api/item/{$itemId}", [])->assertStatus(401);
    });
});

describe('Mark Item as Found', function () {
    it('can mark an item as found', function () {
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $item = Item::factory()->create([
            'id_user' => $user->id,
            'is_found' => false,
            'found_at' => null,
        ]);

        $response = $this->patchJson("/api/item/{$item->id}/found");

        $response->assertOk()
            ->assertJson(['data' => true]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_found' => true,
        ]);
    });

    it('returns 404 if item not found', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->patchJson("/api/item/nonexistent/found");

        $response->assertStatus(404)
            ->assertJsonStructure(['errors']);
    });

    it('returns 403 if user is not the owner', function () {
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($otherUser);


        $item = Item::factory()->create([
            'id_user' => $owner->id,
            'is_found' => false,
        ]);

        $response = $this->patchJson("/api/item/{$item->id}/found");

        $response->assertStatus(403);
    });

    it('requires authentication', function () {
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);
        $item = Item::factory()->create();

        $response = $this->patchJson("/api/item/{$item->id}/found");

        $response->assertStatus(401);
    });
});
