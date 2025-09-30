<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BudgetItemResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'project_id' => $this->project_id,
			'category_id' => $this->category_id,
			'name' => $this->name,
			'description' => $this->description,
			'unit' => $this->unit,
			'qty_planned' => $this->qty_planned,
			'rate_cents' => $this->rate_cents,
			'qty_actual' => $this->qty_actual,
			'cost_actual_cents' => $this->cost_actual_cents,
			'vendor_name' => $this->vendor_name,
			'receipt_path' => $this->receipt_path,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
