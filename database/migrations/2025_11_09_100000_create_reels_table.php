<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reels', function (Blueprint $table) {
            $table->id();

            // Link to the author (user)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Reel fields
            $table->string('video_url');
            $table->text('caption')->nullable();
            $table->json('hashtags')->nullable();

            // Interaction counters
            $table->boolean('allow_comment')->default(true);
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('comment_count')->default(0);
            $table->unsignedInteger('share_count')->default(0);

            // States
            $table->boolean('is_liked')->default(false);
            $table->boolean('is_seen')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reels');
    }
};
