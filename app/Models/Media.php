<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'url',
        'thumbnail',
        'mediable_id',
        'mediable_type',
    ];

    /**
     * Polymorphic relationship to the parent (post or reel)
     */
    public function mediable()
    {
        return $this->morphTo();
    }

    public function media()
{
    return $this->morphMany(PostMedia::class, 'mediable');
}
}
