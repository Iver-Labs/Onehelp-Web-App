<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('organization_verifications', function (Blueprint $table) {
            $table->id('verification_id');
            $table->foreignId('organization_id')->constrained('organizations', 'organization_id')->onDelete('cascade');
            $table->string('document_type');
            $table->string('document_url');
            $table->string('verification_status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('organization_verifications');
    }
};
