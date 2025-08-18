<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
	public function __construct(private readonly ProjectRepositoryInterface $projects)
	{
	}

	public function index()
	{
		return ProjectResource::collection($this->projects->paginate());
	}

	public function store(StoreProjectRequest $request)
	{
		$project = $this->projects->create($request->validated());
		return (new ProjectResource($project))
			->response()
			->setStatusCode(201);
	}

	public function show(int $id)
	{
		$project = $this->projects->find($id);
		abort_unless($project, 404);
		return new ProjectResource($project);
	}

	public function update(UpdateProjectRequest $request, int $id)
	{
		$project = $this->projects->find($id);
		abort_unless($project, 404);
		$project = $this->projects->update($project, $request->validated());
		return new ProjectResource($project);
	}

	public function destroy(int $id)
	{
		$project = $this->projects->find($id);
		abort_unless($project, 404);
		$this->projects->delete($project);
		return response()->noContent();
	}
}
