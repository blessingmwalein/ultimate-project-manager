<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\UserPlan;
use App\Models\Plan;

interface UserPlanRepositoryInterface
{
	public function activatePlan(User $user, Plan $plan): UserPlan;
}

