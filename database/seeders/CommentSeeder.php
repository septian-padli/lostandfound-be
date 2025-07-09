<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::orderBy('created_at', 'desc')->get();
        foreach ($items as $item) {
            Comment::factory()->count(2)->create([
                'id_item' => $item->id,
            ]);
        }

        // dapatkan 1 komentar acak di setiap item
        $items->each(function ($item) {
            $comment = Comment::inRandomOrder()->where('id_item', $item->id)->first();
            if ($comment) {
                Comment::factory()->create([
                    'id_parent' => $comment->id,
                    'id_item' => $item->id,
                ]);
            }
        });
    }
}
