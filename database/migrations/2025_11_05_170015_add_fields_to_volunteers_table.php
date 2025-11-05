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
        Schema::table('volunteers', function (Blueprint $table) {
            // Add new fields if they don't exist
            if (!Schema::hasColumn('volunteers', 'age')) {
                $table->integer('age')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('volunteers', 'phone')) {
                $table->string('phone', 20)->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('volunteers', 'skills')) {
                $table->string('skills', 500)->nullable()->after('bio');
            }
            if (!Schema::hasColumn('volunteers', 'interests')) {
                $table->string('interests', 500)->nullable()->after('skills');
            }
            if (!Schema::hasColumn('volunteers', 'location')) {
                $table->string('location', 255)->nullable()->after('interests');
            }
            if (!Schema::hasColumn('volunteers', 'availability')) {
                $table->string('availability', 255)->nullable()->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn(['age', 'phone', 'skills', 'interests', 'location', 'availability']);
        });
    }
};