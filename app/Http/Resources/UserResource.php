<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'role' => $this->role->name,
            'outlet' => $this->outlet->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}