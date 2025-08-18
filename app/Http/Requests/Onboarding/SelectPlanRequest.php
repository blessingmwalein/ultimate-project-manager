<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class SelectPlanRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'plan_code' => ['required','exists:plans,code'],
		];
	}
}
