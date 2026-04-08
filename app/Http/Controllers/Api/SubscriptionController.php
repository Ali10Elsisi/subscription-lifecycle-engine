<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Service\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
 
class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
        private readonly SubscriptionRepositoryInterface $subscriptionRepository,
    ) {}
 

public function current(Request $request): JsonResponse
{
    $userId = $request->query('user_id'); 

    $subscription = $this->subscriptionRepository->findActiveForUser($userId);

    if (! $subscription) {
        return response()->json(['message' => 'No active subscription found.'], 404);
    }

    return response()->json(new SubscriptionResource($subscription));
}
 

    public function subscribe(StoreSubscriptionRequest $request)
    {
        $plan      = Plan::findOrFail($request->validated('plan_id'));
        $planPrice = PlanPrice::findOrFail($request->validated('plan_price_id'));

 
    $subscription = $this->subscriptionService->subscribe(
        $request->validated('user_id'), 
        $plan,
        $planPrice
    );

        // dd token of user
 
        return (new SubscriptionResource($subscription->load(['plan', 'planPrice'])))
            ->response()
            ->setStatusCode(201);
    }
 

    public function show(Subscription $subscription)
    {
        return new SubscriptionResource($subscription->load(['plan', 'planPrice', 'payments']));
    }
 

    public function cancel(Subscription $subscription)
    {
        if ($subscription->isCanceled()) {
            return response()->json(['message' => 'Subscription is already canceled.'], 422);
        }
 
        $subscription = $this->subscriptionService->cancel($subscription);
 
        return response()->json([
            'message'      => 'Subscription canceled successfully.',
            'subscription' => new SubscriptionResource($subscription),
        ]);
    }
}
 
