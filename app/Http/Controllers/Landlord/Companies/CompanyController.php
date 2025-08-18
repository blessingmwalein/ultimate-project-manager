<?php

namespace App\Http\Controllers\Landlord\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Support\ApiResponse;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
	public function __construct(private readonly CompanyRepositoryInterface $companies) {}

	public function index()
	{
		return ApiResponse::paginated($this->companies->paginate());
	}

	public function store(StoreCompanyRequest $request)
	{
		$data = $request->validated();
		$data['slug'] = $data['slug'] ?? Str::slug($data['name']);
		$company = $this->companies->create($data);
		return ApiResponse::created($company);
	}

	public function show(int $id)
	{
		$company = $this->companies->find($id);
		abort_unless($company, 404);
		return ApiResponse::success($company);
	}

	public function update(UpdateCompanyRequest $request, int $id)
	{
		$company = $this->companies->update($id, $request->validated());
		return ApiResponse::success($company);
	}

	public function destroy(int $id)
	{
		$this->companies->delete($id);
		return response()->noContent();
	}
}
