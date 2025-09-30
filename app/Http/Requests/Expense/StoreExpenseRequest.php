<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'budget_item_id' => [
                'sometimes','nullable','integer',
                Rule::exists('budget_items','id')->where(function ($query) {
                    $query->where('project_id', $this->route('projectId'))
                          ->where('company_id', $this->route('companyId'));
                }),
            ],
            'date' => ['required','date'],
            'amount_cents' => ['required','integer','min:1'],
            'currency' => ['nullable','string','size:3'],
            'description' => ['nullable','string'],
            'vendor' => ['nullable','string','max:255'],
            'reference_no' => ['nullable','string','max:255'],
            'receipt_path' => [
                'nullable','string','max:255',
                function ($attribute, $value, $fail) {
                    $company = $this->route('companyId');
                    $project = $this->route('projectId');
                    $prefix = "tenants/{$company}/projects/{$project}/receipts";
                    if (! str_starts_with($value, $prefix)) {
                        $fail('The receipt_path must point to the uploads for this company and project.');
                    }
                }
            ],
        ];
    }
}
