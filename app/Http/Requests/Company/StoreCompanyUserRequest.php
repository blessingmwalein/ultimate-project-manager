<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email' => ['required','email','max:255'],
            'name' => ['nullable','string','max:255'],
            'role' => ['required','in:admin,project_manager,site_supervisor,viewer,client'],
        ];
    }
}
