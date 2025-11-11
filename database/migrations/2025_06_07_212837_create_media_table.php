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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['image', 'video']); // media type
            $table->string('url'); // media URL
            $table->string('thumbnail')->nullable(); // optional thumbnail
            $table->morphs('mediable'); // mediable_id & mediable_type (post or reel)
            $table->timestamps();

            // Optional indexes
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
