<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $t->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $t->foreignId('budget_item_id')->nullable()->constrained('budget_items')->nullOnDelete();
            $t->date('date');
            $t->bigInteger('amount_cents');
            $t->string('currency', 3)->default('USD');
            $t->text('description')->nullable();
            $t->string('vendor')->nullable();
            $t->string('reference_no')->nullable();
            $t->string('receipt_path')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users');
            $t->foreignId('approved_by')->nullable()->constrained('users');
            $t->timestamps();
            $t->index(['company_id','project_id']);
            $t->index(['budget_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
