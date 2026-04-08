<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\SubscriptionStatus;

class Subscription extends Model
{
    //
    protected $guarded = [];
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function planPrice()
    {
        return $this->belongsTo(PlanPrice::class);
    }

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
 
 
    public function isTrialing(): bool
    {
        return $this->status === SubscriptionStatus::Trialing;
    }
 
    public function isActive(): bool
    {
        return $this->status === SubscriptionStatus::Active;
    }
 
    public function isPastDue(): bool
    {
        return $this->status === SubscriptionStatus::PastDue;
    }
 
    public function isCanceled(): bool
    {
        return $this->status === SubscriptionStatus::Canceled;
    }
 
    public function hasAccess(): bool
    {
        return $this->status->isAccessible();
    }
 
    public function trialHasExpired(): bool
    {
        return $this->isTrialing()
            && $this->trial_ends_at !== null
            && $this->trial_ends_at->isPast();
    }
 
    public function gracePeriodHasExpired(): bool
    {
        return $this->isPastDue()
            && $this->grace_period_ends_at !== null
            && $this->grace_period_ends_at->isPast();
    }

    
}
