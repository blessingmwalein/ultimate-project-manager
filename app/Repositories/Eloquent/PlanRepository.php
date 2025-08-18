<?php

namespace App\Repositories\Eloquent;

use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PlanRepository implements PlanRepositoryInterface
{
	public function all(): Collection
	{
		return Plan::query()->orderBy('price_cents')->get();
	}

	public function create(array $attributes): Plan
	{
		return Plan::query()->create($attributes);
	}

	public function update(int $id, array $attributes): void
	{
		$plan = Plan::query()->findOrFail($id);
		$plan->fill($attributes);
		$plan->save();
	}

	public function delete(int $id): void
	{
		Plan::query()->whereKey($id)->delete();
	}
}
