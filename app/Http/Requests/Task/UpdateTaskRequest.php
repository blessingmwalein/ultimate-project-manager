<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
	public function authorize(): bool { return true; }

	public function rules(): array
	{
		return [
			'task_list_id' => ['sometimes','nullable','integer','exists:task_lists,id'],
			'parent_task_id' => ['sometimes','nullable','integer','exists:tasks,id'],
			'title' => ['sometimes','required','string','max:255'],
			'description' => ['nullable','string'],
			'status' => ['in:todo,in_progress,blocked,done'],
			'priority' => ['in:low,normal,high,critical'],
			'start_date' => ['nullable','date'],
			'due_date' => ['nullable','date','after_or_equal:start_date'],
			'assignee_id' => ['sometimes','nullable','integer','exists:users,id'],
			'progress_pct' => ['nullable','integer','between:0,100'],
			'estimate_hours' => ['nullable','numeric','min:0'],
			'actual_hours' => ['nullable','numeric','min:0'],
		];
	}
}
