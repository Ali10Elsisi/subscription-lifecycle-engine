<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RecordPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Subscription;
use App\Service\SubscriptionService;
use Illuminate\Http\JsonResponse;


class PaymentController extends Controller
{
    //
        public function __construct(private readonly SubscriptionService $subscriptionService) {}
 

    public function recordSuccess(RecordPaymentRequest $request, Subscription $subscription): JsonResponse
    {

        if ($subscription->isCanceled()) {
            return response()->json(['message' => 'Cannot process payment for a canceled subscription.'], 422);
        }
 
        $payment = $this->subscriptionService->recordSuccessfulPayment(
            $subscription,
            $request->validated('reference'),
            $request->validated('metadata', [])
        );
 
        return response()->json([
            'message' => 'Payment recorded. Subscription is now active.',
            'payment' => new PaymentResource($payment),
        ]);
    }
 

    public function recordFailure(RecordPaymentRequest $request, Subscription $subscription): JsonResponse
    {
        if ($subscription->isCanceled()) {
            return response()->json(['message' => 'Cannot process payment for a canceled subscription.'], 422);
        }
 
        $payment = $this->subscriptionService->recordFailedPayment(
            $subscription,
            $request->validated('reference'),
            $request->validated('metadata', [])
        );
 
        return response()->json([
            'message' => 'Payment failure recorded. Grace period started.',
            'payment' => new PaymentResource($payment),
        ], 202);
    }
 

    public function index(Subscription $subscription): JsonResponse
    {
        $payments = $subscription->payments()->latest()->get();
 
        return response()->json(PaymentResource::collection($payments));
    }
}
