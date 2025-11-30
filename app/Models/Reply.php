<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\User;
use App\Models\ReplyAttachment;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'body',
        'nickname',
        'image_path',
        'user_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(ReplyAttachment::class);
    }
}
