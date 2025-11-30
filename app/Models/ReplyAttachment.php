<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reply_id',
        'path',
        'original_name',
    ];

    public function reply()
    {
        return $this->belongsTo(Reply::class);
    }
}
