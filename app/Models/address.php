<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'address_line',
        'city',
        'country',
        'phone_number',
        'created_at',
        'updated_at'
    ];
}
