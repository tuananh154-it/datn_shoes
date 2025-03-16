<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{

    use HasFactory;
    use SoftDeletes; 

    protected $dates = ['deleted_at'];
    protected $table = 'colors';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'status'

    ];
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class);
    }

}