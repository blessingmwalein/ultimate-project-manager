<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\ProjectPhoto;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Gate;

class ProjectMediaController extends Controller
{
	public function __construct(){ $this->middleware(['auth:sanctum']); }

	public function index(int $companyId, int $projectId)
	{ $photos = ProjectPhoto::where('company_id',$companyId)->where('project_id',$projectId)->orderByDesc('taken_at')->orderByDesc('id')->paginate(30); return ApiResponse::paginated($photos); }

	public function store(int $companyId, int $projectId)
	{ if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) abort(403); request()->validate(['url'=>['required','url'],'caption'=>['nullable','string','max:255'],'taken_at'=>['nullable','date']]); $photo = ProjectPhoto::create(['company_id'=>$companyId,'project_id'=>$projectId,'url'=>request('url'),'caption'=>request('caption'),'taken_at'=>request('taken_at')]); return ApiResponse::created($photo); }

	public function destroy(int $companyId, int $projectId, int $id)
	{ if (! Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) abort(403); ProjectPhoto::where('company_id',$companyId)->where('project_id',$projectId)->whereKey($id)->delete(); return response()->noContent(); }
}


