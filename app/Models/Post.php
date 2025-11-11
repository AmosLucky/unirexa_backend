<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'content',
    ];

    /**
     * The user who created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic relationship to media (images/videos for this post).
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Polymorphic relationship to comments for this post.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Polymorphic relationship to likes for this post.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Polymorphic relationship to shares for this post.
     */
    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    /**
     * Get count of comments.
     */
    public function commentsCount()
    {
        return $this->comments()->count();
    }

    /**
     * Get count of likes.
     */
    public function likesCount()
    {
        return $this->likes()->count();
    }

    /**
     * Get count of shares.
     */
    public function sharesCount()
    {
        return $this->shares()->count();
    }
}
