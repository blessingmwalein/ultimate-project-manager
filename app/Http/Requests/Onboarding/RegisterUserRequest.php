<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'name' => ['required','string','max:255'],
			'email' => ['required','email','max:255','unique:users,email'],
			'password' => ['required','string','min:8'],
		];
	}
}
