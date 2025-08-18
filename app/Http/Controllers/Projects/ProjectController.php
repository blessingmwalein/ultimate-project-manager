<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Support\ApiResponse;

class ProjectController extends Controller
{
	public function __construct(private readonly ProjectRepositoryInterface $projects) {}

	public function index(int $companyId)
	{
		return ApiResponse::paginated($this->projects->paginateByCompany($companyId));
	}

	public function store(StoreProjectRequest $request, int $companyId)
	{
		$project = $this->projects->createForCompany($companyId, $request->validated());
		return ApiResponse::created($project);
	}

	public function show(int $companyId, int $id)
	{
		$project = $this->projects->findInCompany($companyId, $id) ?? abort(404);
		return ApiResponse::success($project);
	}

	public function update(UpdateProjectRequest $request, int $companyId, int $id)
	{
		$project = $this->projects->updateInCompany($companyId, $id, $request->validated());
		return ApiResponse::success($project);
	}

	public function destroy(int $companyId, int $id)
	{
		$this->projects->deleteInCompany($companyId, $id);
		return response()->noContent();
	}
}
