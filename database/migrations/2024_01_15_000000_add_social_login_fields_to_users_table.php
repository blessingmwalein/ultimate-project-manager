<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('social_provider')->nullable()->after('remember_token');
            $table->string('social_id')->nullable()->after('social_provider');
            $table->string('avatar')->nullable()->after('social_id');
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->boolean('profile_completed')->default(false)->after('avatar');
            
            $table->index(['social_provider', 'social_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['social_provider', 'social_id']);
            $table->dropColumn([
                'social_provider', 
                'social_id', 
                'avatar', 
                'first_name', 
                'last_name', 
                'profile_completed'
            ]);
        });
    }
};