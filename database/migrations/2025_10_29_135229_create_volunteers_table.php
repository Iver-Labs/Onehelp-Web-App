<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void 
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id('volunteer_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->integer('total_hours')->default(0);
            $table->integer('events_completed')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void 
    {
        Schema::dropIfExists('volunteers');
    }
};