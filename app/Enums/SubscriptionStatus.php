<?php
 
namespace App\Enums;
 
enum SubscriptionStatus: string
{
    case Trialing  = 'trialing';
    case Active    = 'active';
    case PastDue   = 'past_due';
    case Canceled  = 'canceled';
 
    public function label(): string
    {
        return match($this) {
            self::Trialing => 'Trialing',
            self::Active   => 'Active',
            self::PastDue  => 'Past Due',
            self::Canceled => 'Canceled',
        };
    }
 
    public function isAccessible(): bool
    {
        return in_array($this, [self::Trialing, self::Active, self::PastDue]);
    }
}
 

