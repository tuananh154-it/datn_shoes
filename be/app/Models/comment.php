<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'parent_id',
        'content',
        'is_anonymous',
        'is_edited',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_edited' => 'boolean',
    ];

    // Mối quan hệ với bảng User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mối quan hệ với bảng Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Mối quan hệ với bình luận cha ( reply comment -> comment)
    // Bình luận cha có thể có nhiều bình luận con
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Mối quan hệ với bình luận con (comment -> comment)
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function interactions()
    {
        return $this->hasMany(CommentInteraction::class);
    }

    public function likes()
    {
        return $this->interactions()->where('type', 1);
    }

    public function reports()
    {
        return $this->hasMany(CommentInteraction::class)->where('type', 2);
    }
}