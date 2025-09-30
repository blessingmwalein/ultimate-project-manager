<?php

namespace App\Repositories\Eloquent;

use App\Models\Expense;
use App\Models\BudgetItem;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Events\BudgetOverrunEvent;
use Carbon\Carbon;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function listForProject(int $companyId, int $projectId, int $perPage = 15): LengthAwarePaginator
    {
        return Expense::query()
            ->where('company_id', $companyId)
            ->where('project_id', $projectId)
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function create(int $companyId, int $projectId, array $attributes): Expense
    {
        $attributes['company_id'] = $companyId;
        $attributes['project_id'] = $projectId;

        // validate receipt_path belongs to this company/project if provided
        if (! empty($attributes['receipt_path'])) {
            $expectedPrefix = "tenants/{$companyId}/projects/{$projectId}/receipts";
            if (! str_starts_with($attributes['receipt_path'], $expectedPrefix)) {
                abort(422, 'Invalid receipt_path for this project');
            }
        }

        // Normalize date inputs (accept ISO8601 from frontend)
        if (! empty($attributes['date'])) {
            try {
                $attributes['date'] = Carbon::parse($attributes['date'])->toDateString();
            } catch (\Throwable $e) {
                // leave as-is; validation should have caught invalid dates
            }
        }

        $expense = Expense::query()->create($attributes);

        // update budget_item aggregates if linked
        if (! empty($expense->budget_item_id)) {
            $item = BudgetItem::query()->find($expense->budget_item_id);
            if ($item) {
                $item->qty_actual = ($item->qty_actual ?? 0) + ($expense->amount_cents / max(1, $item->rate_cents));
                $item->cost_actual_cents = ($item->cost_actual_cents ?? 0) + $expense->amount_cents;
                $item->save();

                // check overrun vs planned
                $planned_cost = intval($item->qty_planned * $item->rate_cents);
                if ($planned_cost > 0 && $item->cost_actual_cents > intval($planned_cost * 1.1)) {
                    event(new BudgetOverrunEvent($expense->project_id, $expense->company_id, $item->id));
                }
            }
        }

        return $expense;
    }

    public function findInProject(int $companyId, int $projectId, int $id): ?Expense
    {
        return Expense::query()->where('company_id', $companyId)->where('project_id', $projectId)->find($id);
    }

    public function deleteInProject(int $companyId, int $projectId, int $id): void
    {
        $expense = $this->findInProject($companyId, $projectId, $id) ?? abort(404);
        $expense->delete();
    }
}
