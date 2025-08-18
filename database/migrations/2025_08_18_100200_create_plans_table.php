<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('plans', function (Blueprint $t) {
			$t->id();
			$t->string('code')->unique();
			$t->string('name');
			$t->integer('price_cents')->default(0);
			$t->string('currency', 3)->default('USD');
			$t->enum('interval', ['month','year'])->default('month');
			$t->unsignedInteger('max_projects')->default(5);
			$t->unsignedInteger('max_users')->default(5);
			$t->json('features')->nullable();
			$t->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('plans');
	}
};
