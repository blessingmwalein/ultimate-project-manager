<?php

namespace App\Http\Requests\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInspectionRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'title' => ['sometimes','required','string','max:255'],
			'description' => ['nullable','string'],
			'status' => ['sometimes','in:scheduled,pending,completed,overdue'],
			'scheduled_date' => ['nullable','date'],
			'council_officer' => ['nullable','string','max:255'],
			'contact_email' => ['nullable','email','max:255'],
		];
	}
}


