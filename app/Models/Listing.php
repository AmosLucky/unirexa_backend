<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'condition',
        'contact_phone',
        'location',
        'is_sold',
    ];

    // A listing belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relationship to media
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // Polymorphic relationship to comments
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Polymorphic relationship to likes
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Polymorphic relationship to shares
    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }
    
}
