<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMessage extends Model
{
	use HasFactory;
	protected $fillable = ['company_id','project_id','user_id','message','attachment_url'];
	public function user(){ return $this->belongsTo(User::class); }
	public function project(){ return $this->belongsTo(Project::class); }
	public function company(){ return $this->belongsTo(Company::class); }
}


