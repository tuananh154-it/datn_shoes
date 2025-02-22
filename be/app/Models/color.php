<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'color';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at'
    ];
}
