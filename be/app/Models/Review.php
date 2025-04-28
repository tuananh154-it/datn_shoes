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
        'order_id',
        'order_detail_id',
        'product_id', // Đã thêm
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
        'is_hidden',
        'is_replied',
        'is_reported'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_edited' => 'boolean',
        'is_hidden' => 'boolean',
        'is_replied' => 'boolean',
        'is_reported' => 'boolean',
    ];

    // Mối quan hệ với bảng User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mối quan hệ với bảng Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Mối quan hệ với bảng OrderDetail
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
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