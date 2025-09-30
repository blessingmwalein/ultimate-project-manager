<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('project_messages', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
			$t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
			$t->text('message');
			$t->string('attachment_url')->nullable();
			$t->timestamps();
		});
	}
	public function down(): void
	{ Schema::dropIfExists('project_messages'); }
};


