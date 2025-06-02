<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'user' => new UserResource($this->whenLoaded('user')),
            'product_id' => $this->product_id,
            'created_at' => $this->created_at->format('M d, Y'),
            'is_verified' => $this->created_at->diffInDays() > 30
        ];
    }
}