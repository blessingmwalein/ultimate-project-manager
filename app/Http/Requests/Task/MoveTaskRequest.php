<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MoveTaskRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'task_list_id' => [
				'required','integer',
				Rule::exists('task_lists','id')->where(function ($query) {
					$query->where('project_id', $this->route('projectId'))
						->where('company_id', $this->route('companyId'));
				}),
			],
			'order_index' => ['nullable','integer','min:0'],
		];
	}
}
