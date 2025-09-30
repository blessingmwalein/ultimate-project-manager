<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('budget_items', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
			$t->foreignId('category_id')->nullable()->constrained('budget_categories')->nullOnDelete();
			$t->string('name');
			$t->text('description')->nullable();
			$t->string('unit')->nullable();
			$t->decimal('qty_planned', 12, 2)->default(0);
			$t->bigInteger('rate_cents')->default(0);
			$t->decimal('qty_actual', 12, 2)->nullable();
			$t->bigInteger('cost_actual_cents')->nullable();
			$t->string('vendor_name')->nullable();
			$t->string('receipt_path')->nullable();
			$t->timestamps();
			$t->index(['company_id','project_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('budget_items');
	}
};
