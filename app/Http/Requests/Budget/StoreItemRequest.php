<?php

namespace App\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'category_id' => ['sometimes','nullable','integer','exists:budget_categories,id'],
			'name' => ['required','string','max:255'],
			'description' => ['nullable','string'],
			'unit' => ['nullable','string','max:50'],
			'qty_planned' => ['nullable','numeric','min:0'],
			'rate_cents' => ['nullable','integer','min:0'],
			'qty_actual' => ['nullable','numeric','min:0'],
			'cost_actual_cents' => ['nullable','integer','min:0'],
			'vendor_name' => ['nullable','string','max:255'],
			'receipt_path' => ['nullable','string','max:255'],
		];
	}
}
