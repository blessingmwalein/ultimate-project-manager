<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskListResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'project_id' => $this->project_id,
			'name' => $this->name,
			'order_index' => $this->order_index,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
