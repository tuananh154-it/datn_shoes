<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'phone_number',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'created_at',
        'updated_at'
    ];
}
