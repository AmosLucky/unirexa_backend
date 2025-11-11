<?php



// app/Models/Rex.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rex extends Model
{
    protected $fillable = ['rexer_id', 'rexed_id'];

    public function rexer()
    {
        return $this->belongsTo(User::class, 'rexer_id');
    }

    public function rexed()
    {
        return $this->belongsTo(User::class, 'rexed_id');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
