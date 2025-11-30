<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'path',
        'original_name',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
