<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onboarding\RegisterUserRequest;
use App\Http\Requests\Onboarding\LoginRequest;
use App\Http\Requests\Onboarding\CompleteProfileRequest;
use App\Http\Requests\Onboarding\CreateCompanyRequest;
use App\Http\Requests\Onboarding\SelectPlanRequest;
use App\Models\Company;
use App\Models\Plan;
use App\Repositories\Contracts\CompanyPlanRepositoryInterface;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
	public function __construct(
		private readonly UserRepositoryInterface $users,
		private readonly CompanyPlanRepositoryInterface $companyPlans,
		private readonly CompanyRepositoryInterface $companies,
	) {}

	public function register(RegisterUserRequest $request)
	{
		$result = $this->users->register($request->validated());
		return ApiResponse::created(['token' => $result['token'], 'user' => $result['user']]);
	}

	public function login(LoginRequest $request)
	{
		$result = $this->users->login(
			$request->validated()['email'],
			$request->validated()['password'],
			$request->validated()['device_name'] ?? null
		);
		return ApiResponse::success(['token' => $result['token'], 'user' => $result['user']]);
	}

	public function completeProfile(CompleteProfileRequest $request)
	{
		$user = $this->users->updateProfile($request->user(), $request->validated());
		return ApiResponse::success($user);
	}

	public function createCompany(CreateCompanyRequest $request)
	{
		$user = $request->user();
		$data = $request->validated();
		$data['slug'] = $data['slug'] ?? Str::slug($data['name']);
		$company = $this->companies->createForOwner($user, $data);
		return ApiResponse::created($company);
	}

	public function selectPlan(SelectPlanRequest $request, int $companyId)
	{
		return DB::transaction(function () use ($request, $companyId) {
			$company = $this->companies->find($companyId) ?? abort(404);
			$plan = Plan::where('code', $request->validated()['plan_code'])->firstOrFail();
			$subscription = $this->companyPlans->activatePlan($company, $plan);
			return ApiResponse::success(['company_id' => $company->id, 'active_subscription' => $subscription, 'plan' => $plan]);
		});
	}
}
