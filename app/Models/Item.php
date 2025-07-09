<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory, HasUlids;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'id_city', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }
    public function images()
    {
        return $this->hasMany(Image::class, 'id_item');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_item');
    }
};
