<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('companies', function (Blueprint $t) {
			$t->id();
			$t->string('name');
			$t->string('slug')->unique();
			$t->foreignId('owner_user_id')->nullable()->constrained('users');
			$t->string('phone')->nullable();
			$t->string('country', 2)->nullable();
			$t->string('timezone')->default('Africa/Harare');
			$t->string('currency', 3)->default('USD');
			$t->enum('status', ['active','suspended','deleted'])->default('active');
			$t->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('companies');
	}
};
