<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('project_photos', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
			$t->string('url');
			$t->string('caption')->nullable();
			$t->timestamp('taken_at')->nullable();
			$t->timestamps();
		});
	}
	public function down(): void
	{ Schema::dropIfExists('project_photos'); }
};


