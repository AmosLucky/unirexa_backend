<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // recipient
            $table->string('type'); // e.g. new_post, new_follower
            $table->string('title');
            $table->text('body')->nullable();

            // Polymorphic relation
            $table->nullableMorphs('notifiable'); // notifiable_id, notifiable_type

            $table->boolean('is_read')->default(false);
            $table->json('metadata')->nullable(); // optional custom data
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}

