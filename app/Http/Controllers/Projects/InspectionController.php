<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inspection\StoreInspectionRequest;
use App\Http\Requests\Inspection\UpdateInspectionRequest;
use App\Repositories\Contracts\InspectionRepositoryInterface;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class InspectionController extends Controller
{
	public function __construct(private readonly InspectionRepositoryInterface $inspections)
	{
		$this->middleware(['auth:sanctum']);
	}

	public function index(int $companyId, int $projectId)
	{
		return ApiResponse::paginated($this->inspections->paginateByProject($companyId, $projectId));
	}

	public function summary(int $companyId, int $projectId)
	{
		return ApiResponse::success($this->inspections->summaryCounts($companyId, $projectId));
	}

	public function store(StoreInspectionRequest $request, int $companyId, int $projectId)
	{
		if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
			abort(403);
		}
		$inspection = $this->inspections->createInProject($companyId, $projectId, $request->validated());
		return ApiResponse::created($inspection);
	}

	public function show(int $companyId, int $projectId, int $id)
	{
		$inspection = $this->inspections->findInProject($companyId, $projectId, $id) ?? abort(404);
		return ApiResponse::success($inspection);
	}

	public function update(UpdateInspectionRequest $request, int $companyId, int $projectId, int $id)
	{
		if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
			abort(403);
		}
		$inspection = $this->inspections->updateInProject($companyId, $projectId, $id, $request->validated());
		return ApiResponse::success($inspection);
	}

	public function destroy(int $companyId, int $projectId, int $id)
	{
		if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
			abort(403);
		}
		$this->inspections->deleteInProject($companyId, $projectId, $id);
		return response()->noContent();
	}

	public function sendEmail(int $companyId, int $projectId)
	{
		$request = request();
		$request->validate([
			'email' => ['required','email'],
			'message' => ['required','string'],
		]);
		// Basic email send; in real-world use Mailable
		Mail::raw($request->input('message'), function ($m) use ($request) {
			$m->to($request->input('email'))
				->subject('Project Inspection Notice');
		});
		return ApiResponse::success(['sent' => true]);
	}

	public function sendReminder(int $companyId, int $projectId, int $id)
	{
		$inspection = $this->inspections->findInProject($companyId, $projectId, $id) ?? abort(404);
		if ($inspection->contact_email) {
			Mail::raw('Reminder: Upcoming inspection "' . $inspection->title . '" scheduled on ' . optional($inspection->scheduled_date)->toDateString(), function ($m) use ($inspection) {
				$m->to($inspection->contact_email)->subject('Inspection Reminder');
			});
			$inspection->reminder_sent = true;
			$inspection->last_reminder_at = now();
			$inspection->save();
		}
		return ApiResponse::success(['reminder_sent' => (bool) $inspection->reminder_sent]);
	}
}


