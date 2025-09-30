<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		if (! Schema::hasColumn('tasks', 'order_index')) {
			Schema::table('tasks', function (Blueprint $t) {
				$t->unsignedInteger('order_index')->nullable()->after('task_list_id');
			});
		}
	}

	public function down(): void
	{
		if (Schema::hasColumn('tasks', 'order_index')) {
			Schema::table('tasks', function (Blueprint $t) {
				$t->dropColumn('order_index');
			});
		}
	}
};
