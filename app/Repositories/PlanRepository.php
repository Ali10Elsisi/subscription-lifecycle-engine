<?php

namespace App\Repositories;
use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PlanRepository implements PlanRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Plan::paginate($perPage);
    }

    public function findById(int $id): ?Plan
    {
        return Plan::find($id);
    }

    public function create(array $data): Plan
    {
        return Plan::create($data);
    }

    public function update(Plan $plan, array $data): Plan
    {
        $plan->update($data);
        return $plan;
    }

    public function delete(Plan $plan): void
    {
        $plan->delete();
    }
}
?>