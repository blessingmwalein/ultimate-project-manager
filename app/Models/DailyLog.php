<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id','project_id','date','weather','summary','notes','manpower_count','materials_used','issues','photos'
    ];

    protected $casts = [
        'date' => 'date',
        'materials_used' => 'array',
        'issues' => 'array',
        'photos' => 'array',
    ];

    public function project() { return $this->belongsTo(Project::class); }
    public function company() { return $this->belongsTo(Company::class); }
}


