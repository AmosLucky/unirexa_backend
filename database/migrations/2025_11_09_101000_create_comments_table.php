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
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // id
            $table->text('content'); // comment content
            $table->unsignedBigInteger('user_id'); // comment author
            $table->string('username'); // author's username (denormalized)
            $table->string('avatar')->nullable(); // author's avatar (denormalized)
            $table->unsignedBigInteger('post_id')->nullable(); // post ID if comment is for post
            $table->unsignedBigInteger('reel_id')->nullable(); // reel ID if comment is for reel
            $table->enum('type', ['post', 'reel'])->default('post'); // type of comment
            $table->timestamps(); // created_at & updated_at

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('reel_id')->references('id')->on('reels')->onDelete('cascade');

            // Optional indexes
            $table->index('post_id');
            $table->index('reel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
