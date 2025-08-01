<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'type' => $this->type,
            'outlet' => $this->outlet->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}