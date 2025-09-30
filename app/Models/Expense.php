<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id','project_id','budget_item_id','date','amount_cents','currency',
        'description','vendor','reference_no','receipt_path','created_by','approved_by'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'amount_cents' => 'integer',
    ];

    public function project() { return $this->belongsTo(Project::class); }
    public function company() { return $this->belongsTo(Company::class); }
    public function budgetItem() { return $this->belongsTo(BudgetItem::class,'budget_item_id'); }
}
