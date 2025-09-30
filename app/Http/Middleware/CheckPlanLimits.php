<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Project;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     * Usage: middleware('plan.limits:projects')
     */
    public function handle(Request $request, Closure $next, string $feature)
    {
        $companyId = $request->route('companyId');
        if (empty($companyId)) return $next($request);

        $company = Company::find($companyId);
        if (! $company) return abort(404);

        $companyPlan = $company->activePlan();
        if (! $companyPlan) return $next($request); // no active plan -> allow

        $plan = $companyPlan->plan;
        if (! $plan) return $next($request);

        $features = $plan->features ?? [];

        switch (strtolower($feature)) {
            case 'projects':
                $max = intval($plan->max_projects ?? ($features['max_projects'] ?? 0));
                if ($max > 0) {
                    $count = Project::query()->where('company_id', $companyId)->count();
                    if ($count >= $max) {
                        return response()->json(['message' => 'Project limit reached for your plan'], 402);
                    }
                }
                break;

            case 'users':
                $max = intval($plan->max_users ?? ($features['max_users'] ?? 0));
                if ($max > 0) {
                    $count = $company->users()->count();
                    if ($count >= $max) {
                        return response()->json(['message' => 'User limit reached for your plan'], 402);
                    }
                }
                break;

            case 'uploads':
                // If plan declares storage limit in MB under features.storage_mb
                $storageMb = intval($features['storage_mb'] ?? 0);
                if ($storageMb > 0) {
                    // Fallback: we don't track exact usage; conservative approach: allow (TODO: implement usage tracking)
                    // For now do nothing and allow uploads. Implement tracking as next step.
                }
                break;

            default:
                // unknown feature -> allow
                break;
        }

        return $next($request);
    }
}
