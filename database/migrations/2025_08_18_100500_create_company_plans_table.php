<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('company_plans', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('plan_id')->constrained('plans');
			$t->enum('status', ['active','canceled','past_due','trial'])->default('active');
			$t->timestamp('starts_at')->nullable();
			$t->timestamp('ends_at')->nullable();
			$t->timestamp('current_period_start')->nullable();
			$t->timestamp('current_period_end')->nullable();
			$t->timestamp('canceled_at')->nullable();
			$t->json('meta')->nullable();
			$t->timestamps();
			$t->index(['company_id','status']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('company_plans');
	}
};
