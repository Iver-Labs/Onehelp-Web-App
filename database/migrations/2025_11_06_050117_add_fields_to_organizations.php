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
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'founded_year')) {
                $table->year('founded_year')->nullable()->after('org_type');
            }
            if (!Schema::hasColumn('organizations', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0.00)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['founded_year', 'rating']);
        });
    }
};