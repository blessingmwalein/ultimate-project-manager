<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class BudgetOverrunEvent
{
    use InteractsWithSockets, SerializesModels;

    public int $projectId;
    public int $companyId;
    public int $budgetItemId;

    public function __construct(int $projectId, int $companyId, int $budgetItemId)
    {
        $this->projectId = $projectId;
        $this->companyId = $companyId;
        $this->budgetItemId = $budgetItemId;
    }
}
