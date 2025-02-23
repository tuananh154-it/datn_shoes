<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'size';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['name', 'discount_price', 'image_url', 'status'];
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'size_id');
    }
}
