<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('users', function (Blueprint $t) {
			$t->string('position')->nullable()->after('email');
			$t->enum('account_type', ['individual','company'])->default('individual')->after('position');
			$t->string('avatar_url')->nullable()->after('account_type');
			$t->string('phone')->nullable()->after('avatar_url');
		});
	}

	public function down(): void
	{
		Schema::table('users', function (Blueprint $t) {
			$t->dropColumn(['position','account_type','avatar_url','phone']);
		});
	}
};

