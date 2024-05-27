<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(Video::class);
    }

    public function convertedVideos()
    {
        return $this->hasMany(ConvertedVideo::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'video_user',  'video_id', 'user_id')->withTimestamps()->withPivot('id');
    }
}
