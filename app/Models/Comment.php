<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory, HasUlids;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'id_parent');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'id_parent');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
