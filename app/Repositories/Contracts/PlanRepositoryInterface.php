<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Plan;

interface PlanRepositoryInterface
{
	public function all(): Collection;
	public function create(array $attributes): Plan;
	public function update(int $id, array $attributes): void;
	public function delete(int $id): void;
}
