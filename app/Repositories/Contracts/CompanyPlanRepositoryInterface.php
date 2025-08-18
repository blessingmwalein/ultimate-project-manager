<?php

namespace App\Repositories\Contracts;

use App\Models\Company;
use App\Models\CompanyPlan;
use App\Models\Plan;

interface CompanyPlanRepositoryInterface
{
	public function activatePlan(Company $company, Plan $plan): CompanyPlan;
}
