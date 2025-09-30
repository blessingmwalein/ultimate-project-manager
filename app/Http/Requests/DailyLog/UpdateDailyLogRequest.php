<?php

namespace App\Http\Requests\DailyLog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDailyLogRequest extends FormRequest
{
	public function authorize(): bool { return true; }
	public function rules(): array
	{
		return [
			'date' => ['sometimes','required','date'],
			'weather' => ['nullable','string','max:255'],
			'summary' => ['nullable','string','max:255'],
			'notes' => ['nullable','string'],
			'manpower_count' => ['nullable','integer','min:0'],
			'materials_used' => ['nullable','array'],
			'issues' => ['nullable','array'],
			'photos' => ['nullable','array'],
		];
	}
}


