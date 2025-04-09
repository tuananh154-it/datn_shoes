<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'type', // 1 = Like, 2 = Report
    ];

    protected $casts = [
        'type' => 'integer',
    ];

    // Mối quan hệ với bảng User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mối quan hệ với bảng Comment
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}