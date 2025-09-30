<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'project_id',
        'title',
        'description',
        'status',
        'scheduled_date',
        'council_officer',
        'contact_email',
        'reminder_sent',
        'last_reminder_at',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'reminder_sent' => 'boolean',
        'last_reminder_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}


