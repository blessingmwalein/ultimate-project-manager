<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\DailyLog\StoreDailyLogRequest;
use App\Http\Requests\DailyLog\UpdateDailyLogRequest;
use App\Repositories\Contracts\DailyLogRepositoryInterface;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Gate;

class DailyLogController extends Controller
{
	public function __construct(private readonly DailyLogRepositoryInterface $logs) { $this->middleware(['auth:sanctum']); }

	public function index(int $companyId, int $projectId)
	{ return ApiResponse::paginated($this->logs->paginateByProject($companyId,$projectId)); }

	public function store(StoreDailyLogRequest $request, int $companyId, int $projectId)
	{ if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) abort(403); $log=$this->logs->createInProject($companyId,$projectId,$request->validated()); return ApiResponse::created($log); }

	public function show(int $companyId, int $projectId, int $id)
	{ $log=$this->logs->findInProject($companyId,$projectId,$id) ?? abort(404); return ApiResponse::success($log); }

	public function update(UpdateDailyLogRequest $request, int $companyId, int $projectId, int $id)
	{ if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) abort(403); $log=$this->logs->updateInProject($companyId,$projectId,$id,$request->validated()); return ApiResponse::success($log); }

	public function destroy(int $companyId, int $projectId, int $id)
	{ if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) abort(403); $this->logs->deleteInProject($companyId,$projectId,$id); return response()->noContent(); }
}


