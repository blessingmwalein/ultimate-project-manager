<?php

namespace App\Repositories\Contracts;

use App\Models\Expense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ExpenseRepositoryInterface
{
    public function listForProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator;
    public function create(int $companyId, int $projectId, array $attributes): Expense;
    public function findInProject(int $companyId, int $projectId, int $id): ?Expense;
    public function deleteInProject(int $companyId, int $projectId, int $id): void;
}
