<?php

namespace App\Repositories\Contracts;

use App\Models\Inspection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InspectionRepositoryInterface
{
	public function paginateByProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator;
	public function createInProject(int $companyId, int $projectId, array $attributes): Inspection;
	public function findInProject(int $companyId, int $projectId, int $id): ?Inspection;
	public function updateInProject(int $companyId, int $projectId, int $id, array $attributes): Inspection;
	public function deleteInProject(int $companyId, int $projectId, int $id): void;
	public function summaryCounts(int $companyId, int $projectId): array;
}


