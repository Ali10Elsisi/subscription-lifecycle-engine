<?php
 
namespace App\Repositories;
 
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
 
class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function findById(int $id): ?Subscription
    {
        return Subscription::with(['plan', 'planPrice', 'payments'])->find($id);
    }
 
    public function findActiveForUser(int $userId): ?Subscription
    {
        return Subscription::with(['plan', 'planPrice'])
            ->where('user_id', $userId)
            ->whereNotIn('status', [SubscriptionStatus::Canceled->value])
            ->latest()
            ->first();
    }
 
    public function create(array $data): Subscription
    {
        return Subscription::create($data);
    }
 
    public function update(Subscription $subscription, array $data): Subscription
    {
        $subscription->update($data);
 
        return $subscription->refresh();
    }
 
    public function getExpiredTrials(): Collection
    {
        return Subscription::where('status', SubscriptionStatus::Trialing->value)
            ->where('trial_ends_at', '<=', now())
            ->get();
    }
 
    public function getExpiredGracePeriods(): Collection
    {
        return Subscription::where('status', SubscriptionStatus::PastDue->value)
            ->where('grace_period_ends_at', '<=', now())
            ->get();
    }
}
 