<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

    protected $table = 'vouchers';

    public $timestamps = true;
    protected $fillable = [
        'name', 'discount_amount', 'discount_percent', 'expiration_date', 
        'min_purchase_amount', 'max_discount_amount', 'terms_and_conditions', 'status'

    ];

    // Quan hệ: Một voucher có thể có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
