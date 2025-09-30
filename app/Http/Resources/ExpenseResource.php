<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'budget_item_id' => $this->budget_item_id,
            'date' => $this->date,
            'amount_cents' => $this->amount_cents,
            'currency' => $this->currency,
            'description' => $this->description,
            'vendor' => $this->vendor,
            'reference_no' => $this->reference_no,
            'receipt_path' => $this->receipt_path,
            'created_by' => $this->created_by,
            'approved_by' => $this->approved_by,
            'created_at' => $this->created_at,
        ];
    }
}
