<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_url',
        'caption',
        'hashtags',
        'allow_comment',
        'like_count',
        'comment_count',
        'share_count',
        'is_liked',
        'is_seen',
    ];

    protected $casts = [
        'hashtags' => 'array',
        'allow_comment' => 'boolean',
        'is_liked' => 'boolean',
        'is_seen' => 'boolean',
    ];

    /**
     * Each reel belongs to one user (the author).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
