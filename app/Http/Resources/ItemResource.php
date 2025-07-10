<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => [
                'slug' => $this->category->slug,
                'name' => $this->category->name,
            ],
            'address' => $this->address,
            'city' => [
                'slug' => $this->city->slug,
                'name' => $this->city->name,
            ],
            'province' => [
                'slug' => $this->province->slug,
                'name' => $this->province->name,
            ],
            'images' => $this->images->map(fn($image) => [
                'id' => $image->id,
                'url' => $image->url,
            ]),
            'user' => [
                'name' => $this->user->name,
                'username' => $this->user->username,
                'photoprofile' => $this->user->photoprofile,
            ],
            'isFound' => $this->is_found,
            'foundAt' => $this->found_at,
            'isActive' => $this->is_active,
            'createdAt' => $this->created_at,
            'countComment' => $this->count_comment,
        ];
    }
}
