<?php

namespace App\Enums;

enum BillingCycle:string
{
    //
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public function months(): int
    {
        return match ($this) {
            self::MONTHLY => 1,
            self::YEARLY => 12,
        };
    }
}
