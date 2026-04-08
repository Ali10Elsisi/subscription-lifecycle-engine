<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentStatus;

class Payment extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'status' => PaymentStatus::class,
        'metadata' => 'array',
    ];
        public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
    public function succeeded(): bool
    {
        return $this->status === PaymentStatus::Succeeded;
    }
 
    public function failed(): bool
    {
        return $this->status === PaymentStatus::Failed;
    }
}
