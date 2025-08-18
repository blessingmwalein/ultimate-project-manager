<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
	use HasFactory;

	protected $fillable = [
		'name', 'slug', 'owner_user_id', 'phone', 'country', 'timezone', 'currency', 'status',
	];

	public function owner()
	{
		return $this->belongsTo(User::class, 'owner_user_id');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'company_user')->withTimestamps()->withPivot('role');
	}

	public function plans(): HasMany
	{
		return $this->hasMany(CompanyPlan::class);
	}

	public function activePlan(): ?CompanyPlan
	{
		return $this->plans()->where('status', 'active')->latest('id')->first();
	}
}
