<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Models\TaskList;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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

		if (! empty($attributes['task_list_id'])) {
			$exists = TaskList::query()
				->where('id', $attributes['task_list_id'])
				->where('company_id', $companyId)
				->where('project_id', $projectId)
				->exists();
			if (! $exists) abort(422, 'Invalid task_list_id for this project');

			// handle ordering: determine insert position
			$destId = $attributes['task_list_id'];
			$insertAt = $attributes['order_index'] ?? null;
			if (is_null($insertAt)) {
				$max = Task::query()
					->where('project_id', $projectId)
					->where('task_list_id', $destId)
					->max('order_index');
				$insertAt = is_null($max) ? 0 : ($max + 1);
			} else {
				Task::query()
					->where('project_id', $projectId)
					->where('task_list_id', $destId)
					->where('order_index', '>=', $insertAt)
					->increment('order_index');
			}

			$attributes['order_index'] = $insertAt;
		} else {
			// no task_list -> ensure order_index is null
			$attributes['order_index'] = null;
		}

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

		if (array_key_exists('task_list_id', $attributes) && ! empty($attributes['task_list_id'])) {
			$exists = TaskList::query()
				->where('id', $attributes['task_list_id'])
				->where('company_id', $companyId)
				->where('project_id', $projectId)
				->exists();
			if (! $exists) abort(422, 'Invalid task_list_id for this project');
		}

		// If task_list_id or order_index provided and differs from current, use move logic
		if ((array_key_exists('task_list_id', $attributes) && $attributes['task_list_id'] != $task->task_list_id) || array_key_exists('order_index', $attributes)) {
			$toList = $attributes['task_list_id'] ?? $task->task_list_id;
			$at = $attributes['order_index'] ?? null;
			return $this->moveInProject($companyId, $projectId, $id, $toList, $at);
		}

		$task->fill($attributes);
		$task->save();
		return $task;
	}

	public function deleteInProject(int $companyId, int $projectId, int $id): void
	{
		$task = $this->findInProject($companyId, $projectId, $id) ?? abort(404);
		$task->delete();
	}

	public function moveInProject(int $companyId, int $projectId, int $id, int $toTaskListId, ?int $orderIndex = null): Task
	{
		$task = $this->findInProject($companyId, $projectId, $id) ?? abort(404);

		// validate destination list
		$destList = TaskList::query()
			->where('id', $toTaskListId)
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->first() ?? abort(422, 'Invalid destination task list');

		$sourceListId = $task->task_list_id;

		return DB::transaction(function () use ($task, $sourceListId, $destList, $orderIndex) {
			$projectId = $task->project_id;

			// normalize current index: if null, treat as end of its list
			$currentIndex = $task->order_index;
			if (is_null($currentIndex)) {
				$currentIndex = Task::query()
					->where('project_id', $projectId)
					->where('task_list_id', $task->task_list_id)
					->max('order_index');
				$currentIndex = is_null($currentIndex) ? 0 : ($currentIndex + 1);
			}

			$destId = $destList->id;

			if ($sourceListId === $destId) {
				// same-list move
				if (is_null($orderIndex)) {
					// nothing to change
					return $task;
				}

				$newIndex = $orderIndex;
				if ($newIndex === $currentIndex) {
					return $task;
				}

				if ($newIndex > $currentIndex) {
					// moving down: decrement items between currentIndex+1 .. newIndex
					Task::query()
						->where('project_id', $projectId)
						->where('task_list_id', $destId)
						->where('order_index', '>', $currentIndex)
						->where('order_index', '<=', $newIndex)
						->decrement('order_index');
				} else {
					// moving up: increment items between newIndex .. currentIndex-1
					Task::query()
						->where('project_id', $projectId)
						->where('task_list_id', $destId)
						->where('order_index', '>=', $newIndex)
						->where('order_index', '<', $currentIndex)
						->increment('order_index');
				}

				$task->order_index = $newIndex;
				$task->save();
				return $task;
			}

			// moving between different lists
			// adjust order indices in source list: decrement indices greater than the current task's index
			if (! is_null($sourceListId)) {
				if (! is_null($currentIndex)) {
					Task::query()
						->where('project_id', $projectId)
						->where('task_list_id', $sourceListId)
						->where('id', '!=', $task->id)
						->where('order_index', '>', $currentIndex)
						->decrement('order_index');
				}
			}

			// Insert into destination: shift indices >= orderIndex up by 1 or append
			$insertAt = $orderIndex;
			if (is_null($insertAt)) {
				$max = Task::query()
					->where('project_id', $projectId)
					->where('task_list_id', $destId)
					->max('order_index');
				$insertAt = is_null($max) ? 0 : ($max + 1);
			} else {
				Task::query()
					->where('project_id', $projectId)
					->where('task_list_id', $destId)
					->where('order_index', '>=', $insertAt)
					->increment('order_index');
			}

			$task->task_list_id = $destId;
			$task->order_index = $insertAt;
			$task->save();

			return $task;
		});
	}
}
