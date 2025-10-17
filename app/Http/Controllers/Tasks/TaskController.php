<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\MoveTaskRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class TaskController extends Controller
{
	public function __construct(private readonly TaskRepositoryInterface $tasks) {}

	public function index(Request $request, int $companyId, int $projectId): AnonymousResourceCollection
	{
		$paginator = $this->tasks->paginateByProject($companyId, $projectId);

		$query = $paginator->getCollection();

		if ($request->has('task_list_id')) {
			$query->where('task_list_id', $request->query('task_list_id'));
		}

		return TaskResource::collection($query);
	}

	public function store(StoreTaskRequest $request, int $companyId, int $projectId)
	{
		// if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
		// 	abort(403);
		// }
		$task = $this->tasks->createInProject($companyId, $projectId, $request->validated());
		return (new TaskResource($task))->response()->setStatusCode(201);
	}

	public function show(int $companyId, int $projectId, int $id)
	{
		$task = $this->tasks->findInProject($companyId, $projectId, $id) ?? abort(404, 'Task not found');
		return new TaskResource($task);
	}

	public function update(UpdateTaskRequest $request, int $companyId, int $projectId, int $id)
	{
		// if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
		// 	abort(403);
		// }
		$task = $this->tasks->updateInProject($companyId, $projectId, $id, $request->validated());
		return new TaskResource($task);
	}

	public function destroy(int $companyId, int $projectId, int $id)
	{
		if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
			abort(403);
		}
		$this->tasks->deleteInProject($companyId, $projectId, $id);
		return response()->noContent();
	}

	public function move(MoveTaskRequest $request, int $companyId, int $projectId, int $id)
	{
		if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
			abort(403);
		}
		$task = $this->tasks->findInProject($companyId, $projectId, $id) ?? abort(404, 'Task not found');
		$payload = [
			'task_list_id' => $request->input('task_list_id'),
		];
		$orderIndex = $request->input('order_index');
		$task = $this->tasks->moveInProject($companyId, $projectId, $id, $payload['task_list_id'], $orderIndex);
		return new TaskResource($task);
	}
}
