<?php

namespace App\Models;
use app\Models\Plan;
use App\Enums\Currency;
use App\Enums\BillingCycle;

use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'currency' => Currency::class,
        'billing_cycle' => BillingCycle::class,
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount / 100, 2) . ' ' . $this->currency->value;
    }
    
}
