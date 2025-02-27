<?php

namespace App\Http\Resources;

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
        // return parent::toArray($request);

        return [
            "email"=> $this->email,
            "idUser"=>  $this->idUser,
            "latitude"=> $this->latitude,
            "longitude"=> $this->longitude,
            "name"=> $this->name,
            "role"=> $this->role,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
            "region" => new RegionResource($this->region),
        ];
    }
}
