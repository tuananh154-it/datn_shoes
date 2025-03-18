<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_detail_id',
        'quantity',
        'price'
    ];

    /**
     * CartItem thuộc về một Cart
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * CartItem thuộc về một ProductDetail (biến thể của sản phẩm)
     */
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }

    /**
     * CartItem có thể truy xuất Product thông qua ProductDetail
     */
    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductDetail::class, 'id', 'id', 'product_detail_id', 'product_id');
    }

    /**
     * Tính tổng giá của một CartItem (số lượng * giá)
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }
}
