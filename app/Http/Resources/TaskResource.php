<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'project_id' => $this->project_id,
			'task_list_id' => $this->task_list_id,
			'parent_task_id' => $this->parent_task_id,
			'title' => $this->title,
			'description' => $this->description,
			'status' => $this->status,
			'priority' => $this->priority,
			'start_date' => $this->start_date,
			'due_date' => $this->due_date,
			'assignee_id' => $this->assignee_id,
			'progress_pct' => $this->progress_pct,
			'estimate_hours' => $this->estimate_hours,
			'actual_hours' => $this->actual_hours,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
