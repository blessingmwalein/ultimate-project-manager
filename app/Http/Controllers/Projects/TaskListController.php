<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskList\StoreTaskListRequest;
use App\Http\Requests\TaskList\UpdateTaskListRequest;
use App\Repositories\Contracts\TaskListRepositoryInterface;
use App\Http\Resources\TaskListResource;

class TaskListController extends Controller
{
	public function __construct(private readonly TaskListRepositoryInterface $lists) {}

	public function index(int $companyId, int $projectId)
	{
		$paginator = $this->lists->paginateByProject($companyId, $projectId);
		return TaskListResource::collection($paginator);
	}

	public function store(StoreTaskListRequest $request, int $companyId, int $projectId)
	{
		$list = $this->lists->createInProject($companyId, $projectId, $request->validated());
		return (new TaskListResource($list))->response()->setStatusCode(201);
	}

	public function show(int $companyId, int $projectId, int $id)
	{
		$list = $this->lists->findInProject($companyId, $projectId, $id) ?? abort(404);
		return new TaskListResource($list);
	}

	public function update(UpdateTaskListRequest $request, int $companyId, int $projectId, int $id)
	{
		$list = $this->lists->updateInProject($companyId, $projectId, $id, $request->validated());
		return new TaskListResource($list);
	}

	public function destroy(int $companyId, int $projectId, int $id)
	{
		$this->lists->deleteInProject($companyId, $projectId, $id);
		return response()->noContent();
	}
}
