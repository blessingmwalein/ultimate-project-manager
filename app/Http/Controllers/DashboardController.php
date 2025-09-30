<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function companyStats(int $companyId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        $stats = [
            'projects' => [
                'total' => Project::where('company_id', $companyId)->count(),
                'active' => Project::where('company_id', $companyId)->where('status', 'active')->count(),
                'completed' => Project::where('company_id', $companyId)->where('status', 'completed')->count(),
            ],
            'tasks' => [
                'total' => Task::whereHas('project', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->count(),
                'completed' => Task::whereHas('project', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->where('status', 'completed')->count(),
                'in_progress' => Task::whereHas('project', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->where('status', 'in_progress')->count(),
            ],
            'users' => [
                'total' => $company->users()->count(),
            ],
        ];

        return ApiResponse::success($stats);
    }

    public function projectStats(int $companyId, int $projectId)
    {
        $company = Company::findOrFail($companyId);
        if (! Gate::allows('company.manage', $company)) {
            return ApiResponse::error('Forbidden', 403);
        }

        $project = Project::where('company_id', $companyId)->findOrFail($projectId);

        $stats = [
            'tasks' => [
                'total' => Task::where('project_id', $projectId)->count(),
                'completed' => Task::where('project_id', $projectId)->where('status', 'completed')->count(),
                'in_progress' => Task::where('project_id', $projectId)->where('status', 'in_progress')->count(),
                'pending' => Task::where('project_id', $projectId)->where('status', 'pending')->count(),
            ],
            'task_lists' => [
                'total' => $project->taskLists()->count(),
            ],
        ];

        return ApiResponse::success($stats);
    }
}
