<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('volunteer_skills', function (Blueprint $table) {
            $table->id('volunteer_skill_id');
            $table->foreignId('volunteer_id')->constrained('volunteers', 'volunteer_id')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills', 'skill_id')->onDelete('cascade');
            $table->string('proficiency_level')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('volunteer_skills');
    }
};
