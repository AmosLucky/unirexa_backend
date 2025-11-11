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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_id')->nullable();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('phone')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('is_blocked')->default(false);
            $table->string('status')->default('active');
            $table->string('role')->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('device_token')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->string('token')->nullable();
            $table->date('dob')->nullable();
            // $table->unsignedInteger('followers_count')->default(0);
            // $table->unsignedInteger('following_count')->default(0);
            // $table->unsignedInteger('likes_count')->default(0);
            // $table->unsignedInteger('comments_count')->default(0);
            // $table->unsignedInteger('shares_count')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->decimal('total_earned', 10, 2)->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->string('language')->default('English');
            $table->string('notification_token')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->unsignedInteger('login_attempts')->default(0);
            $table->string('referral_code')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('badge_level')->default('Bronze');
            $table->string('account_type')->default('user');
            $table->json('interest_tags')->nullable();
            $table->unsignedInteger('post_count')->default(0);
            $table->string('gender')->nullable();
            $table->unsignedTinyInteger('profile_setup_stage')->default(1);
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('university')->nullable();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->text('interest')->nullable();
            $table->string('country')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
