<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
	use HasFactory;

	protected $fillable = [
		'company_id','project_id','category_id','name','description','unit',
		'qty_planned','rate_cents','qty_actual','cost_actual_cents','vendor_name','receipt_path'
	];

	public function project() { return $this->belongsTo(Project::class); }
	public function company() { return $this->belongsTo(Company::class); }
	public function category() { return $this->belongsTo(BudgetCategory::class, 'category_id'); }
}
