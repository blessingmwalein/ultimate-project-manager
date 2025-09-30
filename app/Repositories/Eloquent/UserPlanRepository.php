<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\UserPlan;
use App\Models\Plan;
use App\Repositories\Contracts\UserPlanRepositoryInterface;

class UserPlanRepository implements UserPlanRepositoryInterface
{
	public function activatePlan(User $user, Plan $plan): UserPlan
	{
		UserPlan::where('user_id', $user->id)
			->where('status', 'active')
			->update(['status' => 'canceled']);

		return UserPlan::create([
			'user_id' => $user->id,
			'plan_id' => $plan->id,
			' status' => 'active',
			'current_period_start' => now(),
		]);
	}
}

