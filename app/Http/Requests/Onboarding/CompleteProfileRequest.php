<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class CompleteProfileRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'name' => ['sometimes','required','string','max:255'],
			'phone' => ['nullable','string','max:50'],
			'avatar_url' => ['nullable','url'],
		];
	}
}
