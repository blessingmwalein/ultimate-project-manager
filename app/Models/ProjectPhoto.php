<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPhoto extends Model
{
	use HasFactory;
	protected $fillable = ['company_id','project_id','url','caption','taken_at'];
	protected $casts = ['taken_at' => 'datetime'];
}


