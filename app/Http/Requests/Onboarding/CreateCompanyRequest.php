<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCompanyRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'name' => ['required','string','max:255'],
			'slug' => ['nullable','string','max:255', Rule::unique('companies','slug')],
			'phone' => ['nullable','string','max:50'],
			'country' => ['nullable','string','size:2'],
			'timezone' => ['nullable','string','max:64'],
			'currency' => ['nullable','string','size:3'],
		];
	}
}
