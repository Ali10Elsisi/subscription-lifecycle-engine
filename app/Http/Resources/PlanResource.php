<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PlanPriceResource;

class PlanResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'trial_days' => $this->trial_days,
            'is_active' => $this->is_active,
            'prices' => PlanPriceResource::collection($this->whenLoaded('prices')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
