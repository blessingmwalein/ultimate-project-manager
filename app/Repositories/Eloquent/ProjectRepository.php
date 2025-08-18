<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectRepository implements ProjectRepositoryInterface
{
	public function paginateByCompany(int $companyId, int $perPage = 15): LengthAwarePaginator
	{
		return Project::query()->where('company_id', $companyId)->orderByDesc('id')->paginate($perPage);
	}

	public function createForCompany(int $companyId, array $attributes): Project
	{
		$attributes['company_id'] = $companyId;
		return Project::query()->create($attributes);
	}

	public function findInCompany(int $companyId, int $id): ?Project
	{
		return Project::query()->where('company_id', $companyId)->find($id);
	}

	public function updateInCompany(int $companyId, int $id, array $attributes): Project
	{
		$project = $this->findInCompany($companyId, $id) ?? abort(404);
		$project->fill($attributes);
		$project->save();
		return $project;
	}

	public function deleteInCompany(int $companyId, int $id): void
	{
		$project = $this->findInCompany($companyId, $id) ?? abort(404);
		$project->delete();
	}
}
