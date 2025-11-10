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
        Schema::table('organization_verifications', function (Blueprint $table) {
            $table->renameColumn('verification_status', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organization_verifications', function (Blueprint $table) {
            $table->renameColumn('status', 'verification_status');
        });
    }
};
