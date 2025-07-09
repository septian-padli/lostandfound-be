<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = [];

    // relation to city
    public function cities()
    {
        return $this->hasMany(City::class, 'id_province', 'id');
    }

    // relation to user
    public function users()
    {
        return $this->hasMany(User::class, 'id_province', 'id');
    }
    // relation to item
    public function items()
    {
        return $this->hasMany(Item::class, 'id_province', 'id');
    }
}
