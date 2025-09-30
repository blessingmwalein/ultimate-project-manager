<?php

namespace App\Repositories\Contracts;

use App\Models\TaskList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskListRepositoryInterface
{
	public function paginateByProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator;
	public function createInProject(int $companyId, int $projectId, array $attributes): TaskList;
	public function findInProject(int $companyId, int $projectId, int $id): ?TaskList;
	public function updateInProject(int $companyId, int $projectId, int $id, array $attributes): TaskList;
	public function deleteInProject(int $companyId, int $projectId, int $id): void;
}
