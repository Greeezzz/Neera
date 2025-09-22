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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('caption')->nullable();
            $table->string('media_path')->nullable(); // Image or video path
            $table->string('media_type')->default('image'); // 'image' or 'video'
            $table->timestamp('expires_at'); // Stories expire after 24 hours
            $table->boolean('is_active')->default(true);
            $table->json('viewers')->nullable(); // Track who viewed the story
            $table->timestamps();
            
            $table->index(['user_id', 'is_active', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
