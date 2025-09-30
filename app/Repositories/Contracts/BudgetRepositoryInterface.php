<?php

namespace App\Repositories\Contracts;

use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BudgetRepositoryInterface
{
	public function listCategories(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator;
	public function createCategory(int $companyId, int $projectId, array $attributes): BudgetCategory;
	public function updateCategory(int $companyId, int $projectId, int $id, array $attributes): BudgetCategory;
	public function deleteCategory(int $companyId, int $projectId, int $id): void;

	public function listItems(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator;
	public function createItem(int $companyId, int $projectId, array $attributes): BudgetItem;
	public function updateItem(int $companyId, int $projectId, int $id, array $attributes): BudgetItem;
	public function deleteItem(int $companyId, int $projectId, int $id): void;
}
