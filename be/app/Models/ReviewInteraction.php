<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
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

    // Mối quan hệ với bảng Review
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}