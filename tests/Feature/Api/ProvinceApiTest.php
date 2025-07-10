<?php

use App\Models\City;
use App\Models\User;
use App\Models\Province;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;

describe('Get all provinces', function () {
    it('can retrieve all provinces', function () {
        Sanctum::actingAs(User::factory()->create());

        Province::factory()->create(['name' => 'Jawa Barat']);
        Province::factory()->create(['name' => 'Bali']);

        $response = getJson('/api/province');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name'],
                ],
            ]);
    });

    it('returns 404 if no provinces found', function () {
        Sanctum::actingAs(User::factory()->create());

        $response = getJson('/api/province');

        $response->assertStatus(404)
            ->assertJsonStructure([
                'errors' => ['message'],
            ]);
    });
});

describe('Get single province', function () {
    it('can get province by id', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $province = Province::factory()->create(['slug' => 'province-slug']);
        $cities = City::factory()->count(2)->create(['id_province' => $province->id]);

        $response = getJson("/api/province/{$province->id}", ['Authorization' => 'Bearer testtoken']);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'cities' => [
                    '*' => ['id', 'name'],
                ],
            ],
        ]);
    });

    it('can get province by slug', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $province = Province::factory()->create(['slug' => 'test-slug']);
        $cities = City::factory()->count(2)->create(['id_province' => $province->id]);

        $response = getJson("/api/province/{$province->slug}", ['Authorization' => 'Bearer testtoken']);

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $province->id,
            'name' => $province->name,
        ]);
    });

    it('returns 404 if province not found', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = getJson("/api/province/nonexistent", ['Authorization' => 'Bearer testtoken']);

        $response->assertStatus(404);
        $response->assertJsonStructure(['errors']);
    });
});
