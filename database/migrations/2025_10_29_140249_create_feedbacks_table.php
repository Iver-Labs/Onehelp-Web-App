<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('registration_id')->constrained('event_registrations', 'registration_id')->onDelete('cascade');
            $table->integer('rating')->default(0);
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('feedbacks');
    }
};
