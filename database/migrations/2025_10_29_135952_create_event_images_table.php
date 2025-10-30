<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('event_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->onDelete('cascade');
            $table->string('image_url');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('event_images');
    }
};
