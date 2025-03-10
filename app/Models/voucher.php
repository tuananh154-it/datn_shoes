<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vouchers';
    protected $fillable = [
        'name', 'discount_amount', 'discount_percent', 'expiration_date', 
        'min_purchase_amount', 'max_discount_amount', 'terms_and_conditions', 'status'
    ];
    protected $dates = ['deleted_at'];
}
