<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		$id = (int) $this->route('id');
		return [
			'name' => ['sometimes','required','string','max:255'],
			'slug' => ['nullable','string','max:255', Rule::unique('companies','slug')->ignore($id)],
			'owner_user_id' => ['nullable','exists:users,id'],
			'phone' => ['nullable','string','max:50'],
			'country' => ['nullable','string','size:2'],
			'timezone' => ['nullable','string','max:64'],
			'currency' => ['nullable','string','size:3'],
			'status' => ['nullable', Rule::in(['active','suspended','deleted'])],
		];
	}
}
