<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'code' => ['nullable','string','max:100'],
			'title' => ['required','string','max:255'],
			'description' => ['nullable','string'],
			'status' => ['in:planned,in_progress,on_hold,completed,archived'],
			'location_text' => ['nullable','string','max:255'],
			'latitude' => ['nullable','numeric','between:-90,90'],
			'longitude' => ['nullable','numeric','between:-180,180'],
			'budget_total_cents' => ['nullable','integer','min:0'],
			'currency' => ['nullable','string','size:3'],
			'start_date' => ['nullable','date'],
			'end_date' => ['nullable','date','after_or_equal:start_date'],
		];
	}
}
