<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\StoreCategoryRequest;
use App\Http\Requests\Budget\UpdateCategoryRequest;
use App\Http\Requests\Budget\StoreItemRequest;
use App\Http\Requests\Budget\UpdateItemRequest;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use App\Http\Resources\BudgetCategoryResource;
use App\Http\Resources\BudgetItemResource;

class BudgetController extends Controller
{
	public function __construct(private readonly BudgetRepositoryInterface $budget) {}

	public function categories(int $companyId, int $projectId)
	{
		return BudgetCategoryResource::collection($this->budget->listCategories($companyId, $projectId));
	}

	public function storeCategory(StoreCategoryRequest $request, int $companyId, int $projectId)
	{
		$cat = $this->budget->createCategory($companyId, $projectId, $request->validated());
		return (new BudgetCategoryResource($cat))->response()->setStatusCode(201);
	}

	public function updateCategory(UpdateCategoryRequest $request, int $companyId, int $projectId, int $id)
	{
		$cat = $this->budget->updateCategory($companyId, $projectId, $id, $request->validated());
		return new BudgetCategoryResource($cat);
	}

	public function deleteCategory(int $companyId, int $projectId, int $id)
	{
		$this->budget->deleteCategory($companyId, $projectId, $id);
		return response()->noContent();
	}

	public function items(int $companyId, int $projectId)
	{
		return BudgetItemResource::collection($this->budget->listItems($companyId, $projectId));
	}

	public function storeItem(StoreItemRequest $request, int $companyId, int $projectId)
	{
		$item = $this->budget->createItem($companyId, $projectId, $request->validated());
		return (new BudgetItemResource($item))->response()->setStatusCode(201);
	}

	public function updateItem(UpdateItemRequest $request, int $companyId, int $projectId, int $id)
	{
		$item = $this->budget->updateItem($companyId, $projectId, $id, $request->validated());
		return new BudgetItemResource($item);
	}

	public function deleteItem(int $companyId, int $projectId, int $id)
	{
		$this->budget->deleteItem($companyId, $projectId, $id);
		return response()->noContent();
	}
}
