<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Support\ApiResponse;

class TaskController extends Controller
{
	public function __construct(private readonly TaskRepositoryInterface $tasks) {}

	public function index(int $companyId, int $projectId)
	{
		return ApiResponse::paginated($this->tasks->paginateByProject($companyId, $projectId));
	}

	public function store(StoreTaskRequest $request, int $companyId, int $projectId)
	{
		$task = $this->tasks->createInProject($companyId, $projectId, $request->validated());
		return ApiResponse::created($task);
	}

	public function show(int $companyId, int $projectId, int $id)
	{
		$task = $this->tasks->findInProject($companyId, $projectId, $id) ?? abort(404);
		return ApiResponse::success($task);
	}

	public function update(UpdateTaskRequest $request, int $companyId, int $projectId, int $id)
	{
		$task = $this->tasks->updateInProject($companyId, $projectId, $id, $request->validated());
		return ApiResponse::success($task);
	}

	public function destroy(int $companyId, int $projectId, int $id)
	{
		$this->tasks->deleteInProject($companyId, $projectId, $id);
		return response()->noContent();
	}
}
