<?php

namespace App\Models;

use App\Models\City;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Province;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUlids, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'email',
        'googleId',
        'name',
        'username',
        'photoprofile',
        'token',
        'id_city',
        'id_province',
        'isAdmin',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'id_city', 'id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'id_user');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_user');
    }
}
