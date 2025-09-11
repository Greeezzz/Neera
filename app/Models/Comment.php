<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'content', 'media', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function votes()
    {
        return $this->hasMany(CommentVote::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'votes', 'replies');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
