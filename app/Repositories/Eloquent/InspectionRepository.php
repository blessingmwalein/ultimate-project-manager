<?php

namespace App\Repositories\Eloquent;

use App\Models\Inspection;
use App\Repositories\Contracts\InspectionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InspectionRepository implements InspectionRepositoryInterface
{
	public function paginateByProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator
	{
		return Inspection::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->orderByRaw("CASE WHEN status = 'overdue' THEN 0 WHEN status = 'scheduled' THEN 1 WHEN status = 'pending' THEN 2 ELSE 3 END")
			->orderBy('scheduled_date')
			->paginate($perPage);
	}

	public function createInProject(int $companyId, int $projectId, array $attributes): Inspection
	{
		$attributes['company_id'] = $companyId;
		$attributes['project_id'] = $projectId;
		return Inspection::create($attributes);
	}

	public function findInProject(int $companyId, int $projectId, int $id): ?Inspection
	{
		return Inspection::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->find($id);
	}

	public function updateInProject(int $companyId, int $projectId, int $id, array $attributes): Inspection
	{
		$inspection = $this->findInProject($companyId, $projectId, $id);
		abort_unless($inspection, 404);
		$inspection->fill($attributes);
		$inspection->save();
		return $inspection;
	}

	public function deleteInProject(int $companyId, int $projectId, int $id): void
	{
		Inspection::query()
			->where('company_id', $companyId)
			->where('project_id', $projectId)
			->whereKey($id)
			->delete();
	}

	public function summaryCounts(int $companyId, int $projectId): array
	{
		$base = Inspection::query()->where('company_id', $companyId)->where('project_id', $projectId);
		return [
			'total' => (clone $base)->count(),
			'completed' => (clone $base)->where('status', 'completed')->count(),
			'pending' => (clone $base)->where('status', 'pending')->count(),
			'overdue' => (clone $base)->where('status', 'overdue')->count(),
		];
	}
}


