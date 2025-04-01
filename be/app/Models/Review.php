<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'parent_id',
        'rating',
        'content',
        'reply',
        'service',
        'packaging',
        'shipping',
        'customer_service',
        'is_anonymous',
        'helpful_count',
        'is_edited',
        'is_replied',
        'is_reported'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_edited' => 'boolean',
        'is_replied' => 'boolean',
        'is_reported' => 'boolean',
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

    // Mối quan hệ với bảng Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Mối quan hệ với bình luận cha ( reply comment -> comment)
    // Bình luận cha có thể có nhiều bình luận con
    public function parent()
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }

    // Mối quan hệ với bình luận con (comment -> comment)
    public function children()
    {
        return $this->hasMany(Review::class, 'parent_id');
    }

    public function interactions()
    {
        return $this->hasMany(ReviewInteraction::class);
    }
    public function likes()
    {
        return $this->interactions()->where('type', 1);
    }
    public function reports()
    {
        return $this->interactions()->where('type', 2);
    }
}