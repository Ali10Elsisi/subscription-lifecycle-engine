<?php
 
namespace App\Service;
 
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

 
class SubscriptionService
{
    private const GRACE_PERIOD_DAYS = 3;
 
    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptionRepository
    ) {}
 
    // -------------------------------------------------------------------------
    // Subscribe
    // -------------------------------------------------------------------------
 
    /**
     * Subscribe a user to a plan+price combination.
     * If the plan has a trial, the subscription starts as "trialing".
     * Otherwise it starts as "active" (assumes payment will be handled externally).
     */
    public function subscribe(int $userId, Plan $plan, PlanPrice $planPrice): Subscription
    {
        if ($planPrice->plan_id !== $plan->id) {
            throw new InvalidArgumentException('The selected price does not belong to the given plan.');
        }
 
        return DB::transaction(function () use ($userId, $plan, $planPrice) {
            $now = now();
 
            if ($plan->hasTrial()) {
                $status       = SubscriptionStatus::Trialing;
                $trialEndsAt  = $now->copy()->addDays($plan->trial_days);
                $periodStart  = null;
                $periodEnd    = null;
            } else {
                $status       = SubscriptionStatus::Active;
                $trialEndsAt  = null;
                $periodStart  = $now;
                $periodEnd    = $now->copy()->addMonths($planPrice->billing_cycle->months());
            }
 
            return $this->subscriptionRepository->create([
                'user_id'              => $userId,
                'plan_id'              => $plan->id,
                'plan_price_id'        => $planPrice->id,
                'status'               => $status,
                'trial_ends_at'        => $trialEndsAt,
                'current_period_start' => $periodStart,
                'current_period_end'   => $periodEnd,
            ]);
        });
    }
 
    // -------------------------------------------------------------------------
    // Payment handling
    // -------------------------------------------------------------------------
 
    /**
     * Record a successful payment and activate / renew the subscription.
     */
    public function recordSuccessfulPayment(
        Subscription $subscription,
        string $reference,
        array $metadata = []
    ): Payment {
        return DB::transaction(function () use ($subscription, $reference, $metadata) {
            $planPrice = $subscription->planPrice;
            $now       = now();
 
            $payment = Payment::create([
                'subscription_id' => $subscription->id,
                'user_id'         => $subscription->user_id,
                'amount'          => $planPrice->amount,
                'currency'        => $planPrice->currency->value,
                'status'          => PaymentStatus::Succeeded,
                'reference'       => $reference,
                'metadata'        => $metadata,
            ]);
 
            // Renew period
            $periodStart = $now;
            $periodEnd   = $now->copy()->addMonths($planPrice->billing_cycle->months());
 
            $this->subscriptionRepository->update($subscription, [
                'status'               => SubscriptionStatus::Active,
                'grace_period_ends_at' => null,
                'current_period_start' => $periodStart,
                'current_period_end'   => $periodEnd,
            ]);
 
            return $payment;
        });
    }
 
    /**
     * Record a failed payment and enter grace period.
     */
    public function recordFailedPayment(
        Subscription $subscription,
        string $reference,
        array $metadata = []
    ): Payment {
        return DB::transaction(function () use ($subscription, $reference, $metadata) {
            $planPrice = $subscription->planPrice;
 
            $payment = Payment::create([
                'subscription_id' => $subscription->id,
                'user_id'         => $subscription->user_id,
                'amount'          => $planPrice->amount,
                'currency'        => $planPrice->currency->value,
                'status'          => PaymentStatus::Failed,
                'reference'       => $reference,
                'metadata'        => $metadata,
            ]);
 
            // Only enter grace period if not already in one
            if (! $subscription->isPastDue()) {
                $this->subscriptionRepository->update($subscription, [
                    'status'               => SubscriptionStatus::PastDue,
                    'grace_period_ends_at' => now()->addDays(self::GRACE_PERIOD_DAYS),
                ]);
            }
 
            return $payment;
        });
    }
 
    // -------------------------------------------------------------------------
    // Cancel
    // -------------------------------------------------------------------------
 
    public function cancel(Subscription $subscription): Subscription
    {
        return $this->subscriptionRepository->update($subscription, [
            'status'      => SubscriptionStatus::Canceled,
            'canceled_at' => now(),
        ]);
    }
 
    // -------------------------------------------------------------------------
    // Scheduler transitions (called by the daily cron)
    // -------------------------------------------------------------------------
 
    /**
     * Move all expired trialing subscriptions to "active" state.
     * (In a real system you'd kick off a payment attempt here.)
     */
    public function processExpiredTrials(): int
    {
        $trials    = $this->subscriptionRepository->getExpiredTrials();
        $processed = 0;
 
        foreach ($trials as $subscription) {
            DB::transaction(function () use ($subscription) {
                $planPrice   = $subscription->planPrice;
                $periodStart = now();
                $periodEnd   = $periodStart->copy()->addMonths($planPrice->billing_cycle->months());
 
                $this->subscriptionRepository->update($subscription, [
                    'status'               => SubscriptionStatus::Active,
                    'current_period_start' => $periodStart,
                    'current_period_end'   => $periodEnd,
                ]);
            });
 
            $processed++;
        }
 
        return $processed;
    }
 
    /**
     * Cancel all subscriptions that have passed their grace period.
     */
    public function processExpiredGracePeriods(): int
    {
        $subscriptions = $this->subscriptionRepository->getExpiredGracePeriods();
        $processed     = 0;
 
        foreach ($subscriptions as $subscription) {
            $this->cancel($subscription);
            $processed++;
        }
 
        return $processed;
    }
}
 