<?php
 
namespace App\Repositories\Contracts;
 
use App\Models\Subscription;
use Illuminate\Support\Collection;
 
interface SubscriptionRepositoryInterface
{
    public function findById(int $id): ?Subscription;
 
    public function findActiveForUser(int $userId): ?Subscription;
 
    public function create(array $data): Subscription;
 
    public function update(Subscription $subscription, array $data): Subscription;
 
    /**
     * Trialing subscriptions whose trial_ends_at is in the past.
     */
    public function getExpiredTrials(): Collection;
 
    /**
     * Past_due subscriptions whose grace_period_ends_at is in the past.
     */
    public function getExpiredGracePeriods(): Collection;
}
