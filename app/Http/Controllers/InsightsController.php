<?php

namespace App\Http\Controllers;

use App\Support\ApiResponse;
use Illuminate\Support\Facades\Gate;

class InsightsController extends Controller
{
	public function __construct(){ $this->middleware(['auth:sanctum']); }

	public function project(int $companyId, int $projectId)
	{
		if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) abort(403);
		$insights = [
			'potential_savings' => 3540,
			'recommendations' => [
				['title' => 'Material Cost Optimization','impact' => 'high','confidence' => 0.94,'summary' => 'Switch to local cement supplier for 15% cost reduction.'],
				['title' => 'Weather Delay Risk','impact' => 'medium','confidence' => 0.85,'summary' => '85% chance of rain delays next week. Plan indoor tasks.'],
				['title' => 'Labor Optimization','impact' => 'medium','confidence' => 0.78,'summary' => 'Redistribute tasks to reduce idle time by 20%.'],
			],
		];
		return ApiResponse::success($insights);
	}
}


