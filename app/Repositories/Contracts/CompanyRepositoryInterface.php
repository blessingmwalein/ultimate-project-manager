<?php

namespace App\Repositories\Contracts;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface
{
	public function paginate(int $perPage = 15): LengthAwarePaginator;
	public function create(array $attributes): Company;
	public function createForOwner(User $owner, array $attributes): Company;
	public function find(int $id): ?Company;
	public function update(int $id, array $attributes): Company;
	public function delete(int $id): void;
}
