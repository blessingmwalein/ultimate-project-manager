<?php

namespace App\Repositories\Eloquent;

use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BudgetRepository implements BudgetRepositoryInterface
{
	public function listCategories(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator
	{
		return BudgetCategory::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->orderBy('order_index')
			->paginate($perPage);
	}

	public function createCategory(int $companyId, int $projectId, array $attributes): BudgetCategory
	{
		$attributes['company_id'] = $companyId;
		$attributes['project_id'] = $projectId;
		return BudgetCategory::query()->create($attributes);
	}

	public function updateCategory(int $companyId, int $projectId, int $id, array $attributes): BudgetCategory
	{
		$cat = BudgetCategory::query()->where('company_id', $companyId)->where('project_id', $projectId)->find($id) ?? abort(404);
		$cat->fill($attributes);
		$cat->save();
		return $cat;
	}

	public function deleteCategory(int $companyId, int $projectId, int $id): void
	{
		$cat = BudgetCategory::query()->where('company_id', $companyId)->where('project_id', $projectId)->find($id) ?? abort(404);
		$cat->delete();
	}

	public function listItems(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator
	{
		return BudgetItem::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->orderByDesc('id')
			->paginate($perPage);
	}

	public function createItem(int $companyId, int $projectId, array $attributes): BudgetItem
	{
		$attributes['company_id'] = $companyId;
		$attributes['project_id'] = $projectId;
		return BudgetItem::query()->create($attributes);
	}

	public function updateItem(int $companyId, int $projectId, int $id, array $attributes): BudgetItem
	{
		$item = BudgetItem::query()->where('company_id', $companyId)->where('project_id', $projectId)->find($id) ?? abort(404);
		$item->fill($attributes);
		$item->save();
		return $item;
	}

	public function deleteItem(int $companyId, int $projectId, int $id): void
	{
		$item = BudgetItem::query()->where('company_id', $companyId)->where('project_id', $projectId)->find($id) ?? abort(404);
		$item->delete();
	}
}
