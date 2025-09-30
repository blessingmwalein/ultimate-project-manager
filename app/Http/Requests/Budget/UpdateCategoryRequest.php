<?php

namespace App\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'name' => ['sometimes','required','string','max:255'],
			'order_index' => ['sometimes','nullable','integer','min:0'],
		];
	}
}
