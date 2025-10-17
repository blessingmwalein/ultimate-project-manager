<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CompleteSocialOnboardingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.max' => 'Phone number must not exceed 20 characters.',
            'company_name.max' => 'Company name must not exceed 255 characters.',
            'job_title.max' => 'Job title must not exceed 255 characters.',
        ];
    }
}
