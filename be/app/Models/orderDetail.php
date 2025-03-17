<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    public $timestamps = true;
    protected $fillable = [
        'order_id', 'product_detail_id', 'price', 'quantity', 'total_price'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Quan hệ: Một chi tiết đơn hàng thuộc về một sản phẩm chi tiết
    // public function productDetail()
    // {
    //     return $this->belongsTo(ProductDetail::class);
    // }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'product_id');
    // }
    public function product()
    {
        return $this->belongsToThrough(Product::class, ProductDetail::class);
    }
}
