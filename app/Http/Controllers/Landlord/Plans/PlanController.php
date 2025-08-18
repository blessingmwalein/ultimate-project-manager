<?php

namespace App\Http\Controllers\Landlord\Plans;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
	public function __construct(private readonly PlanRepositoryInterface $plans) {}

	public function index()
	{
		return ApiResponse::success($this->plans->all());
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'code' => ['required','string','max:100', 'unique:plans,code'],
			'name' => ['required','string','max:255'],
			'price_cents' => ['required','integer','min:0'],
			'currency' => ['nullable','string','size:3'],
			'interval' => ['required', Rule::in(['month','year'])],
			'max_projects' => ['required','integer','min:0'],
			'max_users' => ['required','integer','min:0'],
			'features' => ['nullable','array'],
		]);
		$validated['currency'] = $validated['currency'] ?? 'USD';
		$plan = $this->plans->create($validated);
		return ApiResponse::created($plan);
	}

	public function update(Request $request, int $id)
	{
		$validated = $request->validate([
			'code' => ['sometimes','required','string','max:100', Rule::unique('plans','code')->ignore($id)],
			'name' => ['sometimes','required','string','max:255'],
			'price_cents' => ['sometimes','integer','min:0'],
			'currency' => ['nullable','string','size:3'],
			'interval' => ['sometimes', Rule::in(['month','year'])],
			'max_projects' => ['sometimes','integer','min:0'],
			'max_users' => ['sometimes','integer','min:0'],
			'features' => ['nullable','array'],
		]);
		$this->plans->update($id, $validated);
		return ApiResponse::success(['id' => $id]);
	}

	public function destroy(int $id)
	{
		$this->plans->delete($id);
		return response()->noContent();
	}
}
