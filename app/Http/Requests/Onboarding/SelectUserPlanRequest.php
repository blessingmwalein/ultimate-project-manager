<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class SelectUserPlanRequest extends FormRequest
{
	public function authorize(): bool { return true; }
	public function rules(): array
	{ return ['plan_code' => ['required','string','exists:plans,code']]; }
}

