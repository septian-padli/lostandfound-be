<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'username' => $this->username,
            'photoprofile' => $this->photoprofile,
            'isAdmin' => $this->isAdmin,
            'city' => $this->city?->name ?? null,
            'province' => $this->province?->name ?? null,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'tiktok' => $this->tiktok,
            'telegram' => $this->telegram,
        ];
    }
}
