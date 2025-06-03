<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ReviewResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'cart' => new CartResource($this->whenLoaded('cart')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
