<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
	use HasFactory;

	protected $table = 'user_plans';

	protected $fillable = [
		'user_id','plan_id','status','starts_at','ends_at','current_period_start','current_period_end','canceled_at','meta',
	];

	protected $casts = [
		'meta' => 'array',
		'starts_at' => 'datetime',
		'ends_at' => 'datetime',
		'current_period_start' => 'datetime',
		'current_period_end' => 'datetime',
		'canceled_at' => 'datetime',
	];

	public function user() { return $this->belongsTo(User::class); }
	public function plan() { return $this->belongsTo(Plan::class); }
}

