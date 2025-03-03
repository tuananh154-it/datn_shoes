<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'content',
        'created_at',
        'updated_at'
    ];
}