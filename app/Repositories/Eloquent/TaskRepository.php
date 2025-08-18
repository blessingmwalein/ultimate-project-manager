<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
	public function paginateByProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator
	{
		return Task::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->orderByDesc('id')
			->paginate($perPage);
	}

	public function createInProject(int $companyId, int $projectId, array $attributes): Task
	{
		$attributes['company_id'] = $companyId;
		$attributes['project_id'] = $projectId;
		return Task::query()->create($attributes);
	}

	public function findInProject(int $companyId, int $projectId, int $id): ?Task
	{
		return Task::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->find($id);
	}

	public function updateInProject(int $companyId, int $projectId, int $id, array $attributes): Task
	{
		$task = $this->findInProject($companyId, $projectId, $id) ?? abort(404);
		$task->fill($attributes);
		$task->save();
		return $task;
	}

	public function deleteInProject(int $companyId, int $projectId, int $id): void
	{
		$task = $this->findInProject($companyId, $projectId, $id) ?? abort(404);
		$task->delete();
	}
}
