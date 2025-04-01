<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id']; // Thêm session_id để hỗ trợ khách vãng lai

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Giỏ hàng thuộc về một User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy tổng tiền của giỏ hàng
     */
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(fn ($item) => $item->total_price);
    }
}
