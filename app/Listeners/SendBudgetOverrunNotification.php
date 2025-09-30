<?php

namespace App\Listeners;

use App\Events\BudgetOverrunEvent;
use App\Notifications\BudgetOverrunAlert;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBudgetOverrunNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BudgetOverrunEvent $event)
    {
        $project = Project::query()->find($event->projectId);
        if (! $project) return;

        // notify project members - simplified: notify company owner
        $owner = $project->company->owner_user_id ? \App\Models\User::find($project->company->owner_user_id) : null;
        if ($owner) {
            $owner->notify(new BudgetOverrunAlert($project, $event->budgetItemId));
        }
    }
}
