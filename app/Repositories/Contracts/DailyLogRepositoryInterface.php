<?php

namespace App\Repositories\Contracts;

use App\Models\DailyLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DailyLogRepositoryInterface
{
	public function paginateByProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator;
	public function createInProject(int $companyId, int $projectId, array $attributes): DailyLog;
	public function findInProject(int $companyId, int $projectId, int $id): ?DailyLog;
	public function updateInProject(int $companyId, int $projectId, int $id, array $attributes): DailyLog;
	public function deleteInProject(int $companyId, int $projectId, int $id): void;
}


