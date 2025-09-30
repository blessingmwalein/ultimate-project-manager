<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('budget_categories', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
			$t->string('name');
			$t->unsignedInteger('order_index')->default(0);
			$t->timestamps();
			$t->index(['company_id','project_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('budget_categories');
	}
};
