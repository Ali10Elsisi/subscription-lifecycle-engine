<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
    protected $guarded = [];
    public function prices()
    {
        return $this->hasMany(PlanPrice::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(SubScription::class);
    }

    public function hasTrial(): bool
    {
        return $this->trial_days > 0;
    }

}
