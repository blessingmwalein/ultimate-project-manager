<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Models\User;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CompanyRepository implements CompanyRepositoryInterface
{
	public function paginate(int $perPage = 15): LengthAwarePaginator
	{
		return Company::query()->orderByDesc('id')->paginate($perPage);
	}

	public function create(array $attributes): Company
	{
		return Company::query()->create($attributes);
	}

	public function createForOwner(User $owner, array $attributes): Company
	{
		$company = Company::query()->create(array_merge($attributes, ['owner_user_id' => $owner->id]));
		$company->users()->syncWithoutDetaching([$owner->id => ['role' => 'admin']]);
		return $company;
	}

	public function find(int $id): ?Company
	{
		return Company::query()->find($id);
	}

	public function update(int $id, array $attributes): Company
	{
		$company = Company::query()->findOrFail($id);
		$company->fill($attributes);
		$company->save();
		return $company;
	}

	public function delete(int $id): void
	{
		Company::query()->whereKey($id)->delete();
	}
}
