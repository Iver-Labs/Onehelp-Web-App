<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->string('user_type')->default('volunteer'); // volunteer | organization | admin
            $table->timestamp('email_verified_at')->nullable(); // For email verification
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('last_login')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken(); // For "remember me" functionality
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};