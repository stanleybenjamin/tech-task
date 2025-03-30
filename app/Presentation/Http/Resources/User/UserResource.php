<?php

namespace App\Presentation\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'country' => $this->country,
            'selfie' => $this->selfie,
            'email_verified_at' => $this->email_verified_at,
            'is_admin' => $this->is_admin,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at
        ];
    }
}
