<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => $this->product->price,
            'subtotal' => $this->quantity * $this->product->price,
            'product' => new ProductResource($this->whenLoaded('product')),
            'cart_id' => $this->cart_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}