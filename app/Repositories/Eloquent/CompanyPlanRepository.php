<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Models\CompanyPlan;
use App\Models\Plan;
use App\Repositories\Contracts\CompanyPlanRepositoryInterface;

class CompanyPlanRepository implements CompanyPlanRepositoryInterface
{
	public function activatePlan(Company $company, Plan $plan): CompanyPlan
	{
		CompanyPlan::where('company_id', $company->id)
			->where('status', 'active')
			->update(['status' => 'canceled']);

		return CompanyPlan::create([
			'company_id' => $company->id,
			'plan_id' => $plan->id,
			'status' => 'active',
			'current_period_start' => now(),
		]);
	}
}
