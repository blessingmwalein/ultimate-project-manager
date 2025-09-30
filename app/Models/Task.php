<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'company_id','project_id','task_list_id','parent_task_id','title','description','status','priority','start_date','due_date','assignee_id','progress_pct','estimate_hours','actual_hours','order_index'
	];

	protected $casts = [
		'start_date' => 'date',
		'due_date' => 'date',
		'order_index' => 'integer',
	];

	public function company() { return $this->belongsTo(Company::class); }
	public function project() { return $this->belongsTo(Project::class); }
	public function list() { return $this->belongsTo(TaskList::class, 'task_list_id'); }
	public function parent() { return $this->belongsTo(Task::class, 'parent_task_id'); }
	public function assignee() { return $this->belongsTo(User::class, 'assignee_id'); }
}
