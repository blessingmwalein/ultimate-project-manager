<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
	use HasFactory;

	protected $fillable = ['company_id','project_id','name','order_index'];

	public function project() { return $this->belongsTo(Project::class); }
	public function company() { return $this->belongsTo(Company::class); }
}
