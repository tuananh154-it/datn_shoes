<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = true;
    use HasFactory; // Thêm trait này để có thể gọi factory() trong seeder

    protected $fillable = [
        'username',
        'voucher_id',
        'status',
        'deliver_fee',
        'customer_id',
        'payment_status',
        'payment_method',
        'address',
        'phone_number',
        'email',
        'total_price',
        'note',
        'user_id'
    ];
    // Quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng
    // public function orderDetails()
    // {
    //     return $this->hasMany(OrderDetail::class);
    // }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }
    // Quan hệ: Một đơn hàng thuộc về một khách hàng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ: Một đơn hàng có thể có một voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
   
}
