<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

    protected $table = 'vouchers';

    public $timestamps = true;
    protected $fillable = [
        'code', 'discount_amount', 'expiry_date'
    ];

    // Quan hệ: Một voucher có thể có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
