<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	use HasFactory;

	protected $fillable = [
		'code','name','price_cents','currency','interval','max_projects','max_users','features',
	];

	protected $casts = [
		'features' => 'array',
	];
}
