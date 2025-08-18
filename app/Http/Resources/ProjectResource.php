<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'id' => $this->id,
			'code' => $this->code,
			'title' => $this->title,
			'description' => $this->description,
			'status' => $this->status,
			'location' => [
				'text' => $this->location_text,
				'lat' => $this->latitude,
				'lng' => $this->longitude,
			],
			'budget' => [
				'total_cents' => $this->budget_total_cents,
				'currency' => $this->currency,
			],
			'dates' => [
				'start' => $this->start_date,
				'end' => $this->end_date,
			],
			'cover_image_url' => $this->cover_image_url,
			'created_at' => $this->created_at,
		];
	}
}
