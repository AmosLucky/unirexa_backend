<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'firebase_id',
        'email',
        'username',
        'phone',
        'first_name',
        'last_name',
        'avatar',
        'cover_photo',
        'bio',
        'balance',
        'is_blocked',
        'status',
        'role',
        'email_verified_at',
        'device_token',
        'last_seen',
        'token',
        'dob',
        'is_verified',
        'total_spent',
        'total_earned',
        'last_login_at',
        'language',
        'notification_token',
        'ip_address',
        'login_attempts',
        'referral_code',
        'referred_by',
        'badge_level',
        'account_type',
        'interest_tags',
        'post_count',
        'gender',
        'profile_setup_stage',
        'location',
        'website',
        'university',
        'faculty',
        'department',
        'interest',
        'country',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password', // if using password login
        'token',
        'device_token',
        'notification_token',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
        'last_login_at' => 'datetime',
        'dob' => 'date',
        'is_blocked' => 'boolean',
        'is_verified' => 'boolean',
        'balance' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'interest_tags' => 'array',
    ];

    /**
     * Relationships
     */

    // User posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Followers relationship
    public function followers()
    {
        return $this->hasMany(Follower::class, 'following_id'); // users who follow this user
    }

    // Following relationship
    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id'); // users this user is following
    }

    // Likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Shares
    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
