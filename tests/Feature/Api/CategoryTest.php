<?php

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;

describe('Get Categories', function () {

    it('can retrieve all categories', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create([
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);

        $response = $this->getJson('/api/category');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Electronics',
                'slug' => 'electronics',
                'item_count' => 0,
            ]);
    });

    it('returns 404 if there are no categories', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Ensure no categories exist
        Category::query()->delete();

        $response = $this->getJson('/api/category');

        $response->assertStatus(404);
    });
});

describe('Get Category by ID or Slug', function () {

    it('can retrieve a category by ID', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create([
            'name' => 'Books',
            'slug' => 'books',
        ]);

        $response = $this->getJson("/api/category/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Books',
                'slug' => 'books',
                'item_count' => 0,
            ]);
    });

    it('can retrieve a category by slug', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create([
            'name' => 'Clothing',
            'slug' => 'clothing',
        ]);

        $response = $this->getJson("/api/category/{$category->slug}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Clothing',
                'slug' => 'clothing',
                'item_count' => 0,
            ]);
    });

    it('returns 404 if category not found', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/category/nonexistent');

        $response->assertStatus(404);
    });
});

describe('Create Category', function () {
    it('can create a category', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'name' => 'Accessories',
            'slug' => 'accessories',
        ];

        $response = $this->postJson('/api/category', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Accessories',
                'slug' => 'accessories',
                'item_count' => 0,
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Accessories',
            'slug' => 'accessories',
        ]);
    });

    it('returns validation error if payload is invalid', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            // Missing 'name' and 'slug'
        ];

        $response = $this->postJson('/api/category', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'slug']);
    });

    it('requires unique slug', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Category::factory()->create([
            'name' => 'Gadgets',
            'slug' => 'gadgets',
        ]);

        $payload = [
            'name' => 'Another Gadgets',
            'slug' => 'gadgets', // duplicate slug
        ];

        $response = $this->postJson('/api/category', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['slug']);
    });

    it('requires authentication to create category', function () {
        $payload = [
            'name' => 'Toys',
            'slug' => 'toys',
        ];

        $response = $this->postJson('/api/category', $payload);

        $response->assertStatus(401);
    });
});
