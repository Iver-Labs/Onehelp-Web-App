<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('event_skills', function (Blueprint $table) {
            $table->id('event_skill_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills', 'skill_id')->onDelete('cascade');
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('event_skills');
    }
};
