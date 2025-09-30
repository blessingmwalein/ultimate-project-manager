<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Http\Resources\ExpenseResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExpenseController extends Controller
{
    public function __construct(private readonly ExpenseRepositoryInterface $expenses) {}

    public function index(int $companyId, int $projectId): AnonymousResourceCollection
    {
        $paginator = $this->expenses->listForProject($companyId, $projectId);
        return ExpenseResource::collection($paginator);
    }

    public function store(StoreExpenseRequest $request, int $companyId, int $projectId)
    {
        if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
            abort(403);
        }
        $expense = $this->expenses->create($companyId, $projectId, $request->validated());
        return (new ExpenseResource($expense))->response()->setStatusCode(201);
    }

    public function destroy(int $companyId, int $projectId, int $id)
    {
        if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
            abort(403);
        }
        $this->expenses->deleteInProject($companyId, $projectId, $id);
        return response()->noContent();
    }

    public function receipt(int $companyId, int $projectId, int $id)
    {
        if (! \Illuminate\Support\Facades\Gate::allows('company.manage', \App\Models\Company::findOrFail($companyId))) {
            abort(403);
        }
        $expense = $this->expenses->findInProject($companyId, $projectId, $id) ?? abort(404);
        if (empty($expense->receipt_path)) abort(404, 'No receipt attached');

        $disk = config('filesystems.default');
        if (! \Illuminate\Support\Facades\Storage::disk($disk)->exists($expense->receipt_path)) {
            abort(404, 'Receipt file not found');
        }

        $url = \Illuminate\Support\Facades\Storage::disk($disk)->temporaryUrl($expense->receipt_path, now()->addMinutes(15));
        return response()->json(['url' => $url]);
    }
}
