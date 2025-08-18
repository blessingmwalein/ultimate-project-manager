<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
	public function paginateByProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator;
	public function createInProject(int $companyId, int $projectId, array $attributes): Task;
	public function findInProject(int $companyId, int $projectId, int $id): ?Task;
	public function updateInProject(int $companyId, int $projectId, int $id, array $attributes): Task;
	public function deleteInProject(int $companyId, int $projectId, int $id): void;
}
