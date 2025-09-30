<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('inspections', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
			$t->string('title');
			$t->text('description')->nullable();
			$t->enum('status', ['scheduled','pending','completed','overdue'])->default('scheduled');
			$t->date('scheduled_date')->nullable();
			$t->string('council_officer')->nullable();
			$t->string('contact_email')->nullable();
			$t->boolean('reminder_sent')->default(false);
			$t->timestamp('last_reminder_at')->nullable();
			$t->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('inspections');
	}
};


