<?php

namespace App\Repositories\Eloquent;

use App\Models\DailyLog;
use App\Repositories\Contracts\DailyLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DailyLogRepository implements DailyLogRepositoryInterface
{
	public function paginateByProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator
	{
		return DailyLog::query()->where('company_id', $companyId)->where('project_id', $projectId)->orderByDesc('date')->paginate($perPage);
	}
	public function createInProject(int $companyId, int $projectId, array $attributes): DailyLog
	{
		$attributes['company_id'] = $companyId; $attributes['project_id'] = $projectId; return DailyLog::create($attributes);
	}
	public function findInProject(int $companyId, int $projectId, int $id): ?DailyLog
	{
		return DailyLog::query()->where('company_id',$companyId)->where('project_id',$projectId)->find($id);
	}
	public function updateInProject(int $companyId, int $projectId, int $id, array $attributes): DailyLog
	{
		$log = $this->findInProject($companyId,$projectId,$id) ?? abort(404); $log->fill($attributes); $log->save(); return $log;
	}
	public function deleteInProject(int $companyId, int $projectId, int $id): void
	{
		DailyLog::query()->where('company_id',$companyId)->where('project_id',$projectId)->whereKey($id)->delete();
	}
}


