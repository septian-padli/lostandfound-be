<?php

use App\Models\City;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;
use App\Models\Province;
use Laravel\Sanctum\Sanctum;

describe('Comment API', function () {

    beforeEach(function () {
        $province = Province::factory()->create(['slug' => 'province-slug']);
        $city = City::factory()->create(['id_province' => $province->id]);

        $this->user = User::factory()->create();
        $this->item = Item::factory()->create(['id_user' => $this->user->id]);
        Sanctum::actingAs($this->user);
    });

    describe('Create Comment', function () {
        it('can create a parent comment', function () {
            $payload = [
                'itemId' => $this->item->id,
                'userId' => $this->user->id,
                'content' => 'This is a parent comment',
            ];

            $response = $this->postJson('/api/comment', $payload);

            $response->assertCreated()
                ->assertJsonFragment(['content' => 'This is a parent comment']);

            $this->assertDatabaseHas('comments', [
                'content' => 'This is a parent comment',
                'id_item' => $this->item->id,
                'id_user' => $this->user->id,
                'id_parent' => null,
            ]);
        });

        it('can create a reply comment', function () {
            $parentComment = Comment::factory()->create(['id_item' => $this->item->id, 'id_user' => $this->user->id]);

            $payload = [
                'itemId' => $this->item->id,
                'userId' => $this->user->id,
                'content' => 'This is a reply',
                'parentId' => $parentComment->id,
            ];

            $response = $this->postJson('/api/comment', $payload);

            $response->assertCreated()
                ->assertJsonFragment(['content' => 'This is a reply']);

            $this->assertDatabaseHas('comments', [
                'content' => 'This is a reply',
                'id_parent' => $parentComment->id,
            ]);
        });

        it('fails if invalid data', function () {
            $response = $this->postJson('/api/comment', []);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['itemId', 'userId', 'content']);
        });
    });

    describe('Get Comments by Item', function () {
        it('can retrieve comments with replies', function () {
            $parent = Comment::factory()->create(['id_item' => $this->item->id, 'id_user' => $this->user->id]);
            Comment::factory()->count(2)->create([
                'id_item' => $this->item->id,
                'id_user' => $this->user->id,
                'id_parent' => $parent->id,
            ]);

            $response = $this->getJson("/api/comment/getByItem/{$this->item->id}");

            $response->assertOk()
                ->assertJsonStructure([
                    'data' => [['id', 'content', 'user', 'createdAt', 'replies']],
                    'nextCursor',
                ]);
        });

        it('returns 404 if item not found', function () {
            $response = $this->getJson("/api/comment/getByItem/nonexistent");

            $response->assertStatus(404);
        });
    });

    describe('Update Comment', function () {
        it('can update a comment', function () {
            $comment = Comment::factory()->create(['id_item' => $this->item->id, 'id_user' => $this->user->id]);

            $payload = ['content' => 'Updated comment'];

            $response = $this->patchJson("/api/comment/{$comment->id}", $payload);

            $response->assertOk()
                ->assertJsonFragment(['content' => 'Updated comment']);

            $this->assertDatabaseHas('comments', ['id' => $comment->id, 'content' => 'Updated comment']);
        });

        it('returns 404 if comment not found', function () {
            $response = $this->patchJson('/api/comment/nonexistent', ['content' => 'Updated']);

            $response->assertStatus(404);
        });
    });

    describe('Delete Comment', function () {
        it('can soft delete a comment', function () {
            $comment = Comment::factory()->create(['id_item' => $this->item->id, 'id_user' => $this->user->id]);

            $response = $this->deleteJson("/api/comment/{$comment->id}");

            $response->assertOk()
                ->assertJson(['data' => true]);

            $this->assertSoftDeleted('comments', ['id' => $comment->id]);
        });

        it('returns 404 if comment not found', function () {
            $response = $this->deleteJson('/api/comment/nonexistent');

            $response->assertStatus(404);
        });
    });
});
