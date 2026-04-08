<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\SubScription;
use App\Service\PlanService;

class PlanController extends Controller
{
    //

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    public function index(Request $request)
    {
        //
        $plans = $this->planService->listPlans($request->input('per_page', 10));
        return $plans;
        $formattedPlans = PlanResource::collection($plans);
        $paginatedResponse = formatPaginatedData($plans, $formattedPlans);
        return successResponse($paginatedResponse, 'Plans retrieved successfully'); 
    }

    public function store(StorePlanRequest $request)
    {
        $plan = $this->planService->createPlan($request->validated());
        return new PlanResource($plan);
    }

    public function show($id)
    {
        $plan = $this->planService->getPlanById($id);
        if (!$plan) {
            return errorResponse('Plan not found', 404);
        }
        return new PlanResource($plan);
    }

    public function update(UpdatePlanRequest $request, Plan $plan): PlanResource
    {
        $plan = $this->planService->updatePlan($plan, $request->validated());
 
        return new PlanResource($plan);
    }

    public function destroy(Plan $plan)
    {
        $this->planService->deletePlan($plan);
 
        return response()->json(['message' => 'Plan deleted successfully.']);
    }

    
}
