<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use App\Support\ApiResponse;
use App\Repositories\Contracts\CompanyRepositoryInterface;

class CompanyProfileController extends Controller
{
    public function __construct(private readonly CompanyRepositoryInterface $companies)
    {
        $this->middleware('auth:sanctum');
    }

    public function show(int $companyId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        return ApiResponse::success($company);
    }

    public function update(UpdateCompanyRequest $request, int $companyId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        $company = $this->companies->update($companyId, $request->validated());
        return ApiResponse::success($company);
    }
}
