<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
	public function paginateByCompany(int $companyId, int $perPage = 15): LengthAwarePaginator;
	public function createForCompany(int $companyId, array $attributes): Project;
	public function findInCompany(int $companyId, int $id): ?Project;
	public function updateInCompany(int $companyId, int $id, array $attributes): Project;
	public function deleteInCompany(int $companyId, int $id): void;
}
