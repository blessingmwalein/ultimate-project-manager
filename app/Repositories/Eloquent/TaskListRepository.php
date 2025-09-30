<?php

namespace App\Repositories\Eloquent;

use App\Models\TaskList;
use App\Repositories\Contracts\TaskListRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskListRepository implements TaskListRepositoryInterface
{
	public function paginateByProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator
	{
		return TaskList::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->orderBy('order_index')
			->paginate($perPage);
	}

	public function createInProject(int $companyId, int $projectId, array $attributes): TaskList
	{
		$attributes['company_id'] = $companyId;
		$attributes['project_id'] = $projectId;
		return TaskList::query()->create($attributes);
	}

	public function findInProject(int $companyId, int $projectId, int $id): ?TaskList
	{
		return TaskList::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->find($id);
	}

	public function updateInProject(int $companyId, int $projectId, int $id, array $attributes): TaskList
	{
		$list = $this->findInProject($companyId, $projectId, $id) ?? abort(404);
		$list->fill($attributes);
		$list->save();
		return $list;
	}

	public function deleteInProject(int $companyId, int $projectId, int $id): void
	{
		$list = $this->findInProject($companyId, $projectId, $id) ?? abort(404);
		$list->delete();
	}
}
