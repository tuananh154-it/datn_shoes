<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at'
    ];
}
