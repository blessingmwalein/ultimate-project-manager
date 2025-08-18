<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('tasks', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
			$t->foreignId('task_list_id')->nullable()->constrained('task_lists')->nullOnDelete();
			$t->foreignId('parent_task_id')->nullable()->constrained('tasks')->nullOnDelete();
			$t->string('title');
			$t->text('description')->nullable();
			$t->enum('status', ['todo','in_progress','blocked','done'])->default('todo');
			$t->enum('priority', ['low','normal','high','critical'])->default('normal');
			$t->date('start_date')->nullable();
			$t->date('due_date')->nullable();
			$t->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
			$t->unsignedTinyInteger('progress_pct')->default(0);
			$t->decimal('estimate_hours',8,2)->nullable();
			$t->decimal('actual_hours',8,2)->nullable();
			$t->timestamps();
			$t->softDeletes();
			$t->index(['company_id','project_id','status']);
			$t->index(['assignee_id','due_date']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('tasks');
	}
};
