<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('daily_logs', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
			$t->date('date');
			$t->string('weather')->nullable();
			$t->string('summary')->nullable();
			$t->text('notes')->nullable();
			$t->unsignedInteger('manpower_count')->default(0);
			$t->json('materials_used')->nullable();
			$t->json('issues')->nullable();
			$t->json('photos')->nullable();
			$t->timestamps();
			$t->unique(['project_id','date']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('daily_logs');
	}
};


