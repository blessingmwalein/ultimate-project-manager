<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('projects', function (Blueprint $t) {
			$t->id();
			$t->string('code')->nullable();
			$t->string('title');
			$t->text('description')->nullable();
			$t->enum('status', ['planned','in_progress','on_hold','completed','archived'])->default('planned');
			$t->string('location_text')->nullable();
			$t->decimal('latitude', 10, 7)->nullable();
			$t->decimal('longitude', 10, 7)->nullable();
			$t->bigInteger('budget_total_cents')->default(0);
			$t->char('currency', 3)->default('USD');
			$t->date('start_date')->nullable();
			$t->date('end_date')->nullable();
			$t->string('cover_image_url')->nullable();
			$t->foreignId('created_by')->nullable()->constrained('users');
			$t->foreignId('updated_by')->nullable()->constrained('users');
			$t->timestamps();
			$t->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('projects');
	}
};
