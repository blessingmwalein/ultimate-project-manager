<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('company_user', function (Blueprint $t) {
			$t->id();
			$t->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
			$t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
			$t->enum('role', ['admin','project_manager','site_supervisor','viewer','client'])->default('admin');
			$t->timestamps();
			$t->unique(['company_id','user_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('company_user');
	}
};
