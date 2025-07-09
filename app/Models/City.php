<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory, HasUlids;
    protected $guarded = [];

    // relation to province
    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_city', 'id');
    }
    public function items()
    {
        return $this->hasMany(Item::class, 'id_item', 'id');
    }
}
