<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'company_id','code','title','description','status','location_text','latitude','longitude','budget_total_cents','currency','start_date','end_date','cover_image_url'
	];

	protected $casts = [
		'start_date' => 'date',
		'end_date' => 'date',
	];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}
}
