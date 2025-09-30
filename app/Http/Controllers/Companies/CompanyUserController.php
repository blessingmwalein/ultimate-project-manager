<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyUserRequest;
use App\Http\Requests\Company\UpdateCompanyUserRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use App\Support\ApiResponse;
use App\Repositories\Contracts\CompanyUserRepositoryInterface;

class CompanyUserController extends Controller
{
    public function __construct(private readonly CompanyUserRepositoryInterface $companyUsers)
    {
        $this->middleware('auth:sanctum');
        $this->middleware(\App\Http\Middleware\CheckPlanLimits::class . ':users')->only('store');
    }

    public function index(int $companyId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        $users = $this->companyUsers->listCompanyUsers($company);
        return ApiResponse::success($users);
    }

    public function store(StoreCompanyUserRequest $request, int $companyId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        $data = $request->validated();
        $user = $this->companyUsers->inviteUserToCompany($company, $data);

        return ApiResponse::created($user);
    }

    public function show(int $companyId, int $userId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        $user = $this->companyUsers->findCompanyUser($company, $userId);
        if (!$user) {
            return ApiResponse::error('User not found', 404);
        }

        return ApiResponse::success($user);
    }

    public function update(UpdateCompanyUserRequest $request, int $companyId, int $userId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        $data = $request->validated();
        $user = $this->companyUsers->updateCompanyUser($company, $userId, $data);

        return ApiResponse::success($user);
    }

    public function destroy(int $companyId, int $userId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        $this->companyUsers->removeUserFromCompany($company, $userId);
        return response()->noContent();
    }
}
