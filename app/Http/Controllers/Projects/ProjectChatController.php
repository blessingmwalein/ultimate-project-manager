<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Models\ProjectMessage;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Gate;

class ProjectChatController extends Controller
{
	public function __construct(){ $this->middleware(['auth:sanctum']); }

	public function index(int $companyId, int $projectId)
	{ $messages = ProjectMessage::where('company_id',$companyId)->where('project_id',$projectId)->with('user:id,name')->orderByDesc('id')->paginate(30); return ApiResponse::paginated($messages); }

	public function store(SendMessageRequest $request, int $companyId, int $projectId)
	{ if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) abort(403); $msg = ProjectMessage::create(['company_id'=>$companyId,'project_id'=>$projectId,'user_id'=>$request->user()->id,'message'=>$request->input('message'),'attachment_url'=>$request->input('attachment_url')]); return ApiResponse::created($msg->load('user:id,name')); }
}


