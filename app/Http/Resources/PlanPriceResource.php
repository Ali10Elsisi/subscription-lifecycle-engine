<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanPriceResource extends JsonResource
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
            'plan_id' => $this->plan_id,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'billing_cycle' => $this->billing_cycle,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
