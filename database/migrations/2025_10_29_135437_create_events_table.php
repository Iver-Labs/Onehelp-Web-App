<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->foreignId('organization_id')->constrained('organizations', 'organization_id')->onDelete('cascade');
            $table->string('event_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->integer('max_volunteers');
            $table->integer('registered_count')->default(0);
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('events');
    }
};
