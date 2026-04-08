<?php

namespace App\Repositories\Contracts;
use App\Models\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
interface PlanRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
 
    public function findById(int $id): ?Plan;
  
    public function create(array $data): Plan;
 
    public function update(Plan $plan, array $data): Plan;
 
    public function delete(Plan $plan): void;
}

?>