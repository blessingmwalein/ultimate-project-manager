<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		// No longer needed; using company_plans table
	}

	public function down(): void
	{
		// Nothing to rollback
	}
};
