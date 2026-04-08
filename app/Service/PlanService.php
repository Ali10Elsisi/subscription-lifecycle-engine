<?php
namespace App\Service;
use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use App\Models\PlanPrice;



class PlanService
{
    //
    public function __construct(private PlanRepositoryInterface $planRepository){}

    public function paginate(int $perPage = 10)
    {
        return $this->planRepository->paginate($perPage);
    }

    public function listPlans(int $perPage = 10)
    {
        return $this->planRepository->paginate($perPage);
    }
    
    public function getPlanById(int $id): ?Plan
    {
        return $this->planRepository->findById($id);
    }

     public function createPlan(array $data): Plan
    {
        return DB::transaction(function () use ($data) {
            $plan = $this->planRepository->create([
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active'   => $data['is_active'] ?? true,
                'trial_days'  => $data['trial_days'] ?? 0,
            ]);
 
            $this->syncPrices($plan, $data['prices'] ?? []);
 
            return $plan->load('prices');
        });
    }
 
    public function updatePlan(Plan $plan, array $data): Plan
    {
        return DB::transaction(function () use ($plan, $data) {
            $this->planRepository->update($plan, [
                'name'        => $data['name'] ?? $plan->name,
                'description' => $data['description'] ?? $plan->description,
                'is_active'   => $data['is_active'] ?? $plan->is_active,
                'trial_days'  => $data['trial_days'] ?? $plan->trial_days,
            ]);
 
            if (isset($data['prices'])) {
                $this->syncPrices($plan, $data['prices']);
            }
 
            return $plan->load('prices');
        });
    }
    public function deletePlan(Plan $plan): void
    {
        $this->planRepository->delete($plan);
    }

 
    private function syncPrices(Plan $plan, array $prices): void
    {
        foreach ($prices as $price) {
            PlanPrice::updateOrCreate(
                [
                    'plan_id'       => $plan->id,
                    'billing_cycle' => $price['billing_cycle'],
                    'currency'      => $price['currency'],
                ],
                [
                    'amount' => $price['amount'], 
                ]
            );
        }
    }

}




?>