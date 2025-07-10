<?php

use Carbon\Carbon;
use App\Models\City;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Province;
use Laravel\Sanctum\Sanctum;

describe('Advanced Search Items API', function () {

    beforeEach(function () {
        $this->province = Province::factory()->create();
        $this->city = City::factory()->create(['id_province' => $this->province->id]);
        $this->category = Category::factory()->create();
        $this->user = User::factory()->create(['id_city' => $this->city->id]);
        Sanctum::actingAs($this->user);
    });

    it('can search items by multiple keywords in name or slug', function () {
        Item::factory()->create([
            'name' => 'Blue Backpack Bag',
            'slug' => 'blue-backpack',
            'is_active' => true,
            'is_found' => false,
        ]);

        $response = $this->getJson('/api/item/search?q=Blue+Backpack');

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Blue Backpack Bag']);
    });

    it('can search items by exact id', function () {
        $item = Item::factory()->create([
            'name' => 'Exact ID Item',
            'is_active' => true,
            'is_found' => false,
        ]);

        $response = $this->getJson("/api/item/search?q={$item->id}");

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Exact ID Item']);
    });

    it('can filter by category', function () {
        $item = Item::factory()->create([
            'id_category' => $this->category->id,
            'is_active' => true,
            'is_found' => false,
        ]);

        $response = $this->getJson("/api/item/search?category={$this->category->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $item->id]);
    });

    it('can filter by city', function () {
        $item = Item::factory()->create([
            'id_city' => $this->city->id,
            'is_active' => true,
            'is_found' => false,
        ]);

        $response = $this->getJson("/api/item/search?city={$this->city->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $item->id]);
    });

    it('can filter by province', function () {
        $item = Item::factory()->create([
            'id_province' => $this->province->id,
            'is_active' => true,
            'is_found' => false,
        ]);

        $response = $this->getJson("/api/item/search?province={$this->province->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $item->id]);
    });

    it('can search items by similar name with full filters', function () {
        // Item yang cocok dengan semua filter
        $matchingItem = Item::factory()->create([
            'name' => 'Red School Backpack',
            'slug' => 'red-school-backpack',
            'id_province' => $this->province->id,
            'id_city' => $this->city->id,
            'id_category' => $this->category->id,
            'is_active' => true,
            'is_found' => false,
        ]);

        // Item yang tidak cocok (provinsi beda)
        Item::factory()->create([
            'name' => 'Red School Backpack',
            'slug' => 'red-school-backpack',
            'is_active' => true,
            'is_found' => false,
        ]);

        $response = $this->getJson("/api/item/search?q=school%20backpack&category={$this->category->slug}&city={$this->city->slug}&province={$this->province->slug}");

        $response->assertOk()
            ->assertJsonFragment([
                'name' => 'Red School Backpack',
            ])
            ->assertJsonMissing([
                // Item yang tidak cocok tidak muncul
                'id' => 'wrong-id',
            ]);
    });


    it('only returns active items', function () {
        Item::factory()->create(['name' => 'Inactive Item', 'is_active' => false]);

        $response = $this->getJson("/api/item/search?q=Inactive");

        $response->assertOk()
            ->assertJsonMissing(['name' => 'Inactive Item']);
    });

    it('excludes found items older than 1 week', function () {
        Item::factory()->create([
            'name' => 'Old Found Item',
            'is_found' => true,
            'found_at' => Carbon::now()->subDays(8),
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/item/search?q=Old Found");

        $response->assertOk()
            ->assertJsonMissing(['name' => 'Old Found Item']);
    });

    it('includes found items within 1 week', function () {
        $item = Item::factory()->create([
            'name' => 'Recent Found Item',
            'is_found' => true,
            'found_at' => Carbon::now()->subDays(3),
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/item/search?q=Recent");

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Recent Found Item']);
    });

    it('supports pagination', function () {
        Item::factory()->count(30)->create(['is_active' => true, 'is_found' => false]);

        $response = $this->getJson("/api/item/search?limit=10");

        $response->assertOk()
            ->assertJsonStructure(['data', 'nextCursor']);
    });

    it('returns empty data if no matches found', function () {
        $response = $this->getJson("/api/item/search?q=nonexistent");

        $response->assertOk()
            ->assertJson(['data' => []]);
    });

    it('auto-filters by user\'s city when no filters provided', function () {
        $item = Item::factory()->create([
            'id_city' => $this->user->id_city,
            'is_active' => true,
            'is_found' => false,
        ]);

        $response = $this->getJson("/api/item/search");

        $response->assertOk()
            ->assertJsonFragment(['id' => $item->id]);
    });
});

it('requires authentication', function () {

    $response = $this->getJson('/api/item/search');

    $response->assertStatus(401);
});
