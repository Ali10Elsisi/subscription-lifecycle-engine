<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\SubscriptionService;

class SubscriptionLifecycleCommand extends Command
{
    protected $signature = 'subscriptions:process-lifecycle';
    protected $description = 'Process expired trials and grace periods daily.';

    public function handle(SubscriptionService $subscriptionService): int
    {
        $this->info('[1/2] Processing expired trials...');
        $trials = $subscriptionService->processExpiredTrials();
        $this->info("      → {$trials} trial(s) transitioned to active.");

        $this->info('[2/2] Processing expired grace periods...');
        $gracePeriods = $subscriptionService->processExpiredGracePeriods();
        $this->info("      → {$gracePeriods} subscription(s) canceled.");

        return Command::SUCCESS;
    }
}