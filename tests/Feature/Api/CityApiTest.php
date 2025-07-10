<?php

use App\Models\City;
use App\Models\User;
use App\Models\Province;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;

describe('Get all cities', function () {
    it('can retrieve all cities', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $province = Province::factory()->create();
        City::factory()->count(3)->create(['id_province' => $province->id]);

        $response = getJson('/api/city', ['Authorization' => 'Bearer testtoken']);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                ['id', 'name', 'provinceId'],
            ],
        ]);
    });

    it('returns 404 if no cities found', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = getJson('/api/city', ['Authorization' => 'Bearer testtoken']);

        $response->assertStatus(404);
        $response->assertJsonStructure(['errors']);
    });
});

describe('Get single city', function () {
    it('can get city by id', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);

        $response = getJson("/api/city/{$city->id}", ['Authorization' => 'Bearer testtoken']);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'provinceId',
            ],
        ]);
    });

    it('can get city by slug', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);

        $response = getJson("/api/city/{$city->slug}", ['Authorization' => 'Bearer testtoken']);

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $city->id,
            'name' => $city->name,
            'provinceId' => $city->id_province,
        ]);
    });

    it('returns 404 if city not found', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = getJson("/api/city/nonexistent", ['Authorization' => 'Bearer testtoken']);

        $response->assertStatus(404);
        $response->assertJsonStructure(['errors']);
    });
});
