<?php

namespace App\Models\Domain\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'code', 'title', 'description', 'status',
		'location_text', 'latitude', 'longitude',
		'budget_total_cents', 'currency', 'start_date', 'end_date',
		'cover_image_url', 'created_by', 'updated_by',
	];
}
