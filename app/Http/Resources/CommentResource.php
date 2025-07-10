<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'content'   => $this->content,
            'user'      => [
                'id'           => $this->user->id,
                'name'         => $this->user->name,
                'username'     => $this->user->username,
                'photoprofile' => $this->user->photoprofile,
            ],
            'createdAt' => $this->created_at->toIso8601String(),
            'updatedAt' => optional($this->updated_at)->toIso8601String(),
            'replies'   => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
