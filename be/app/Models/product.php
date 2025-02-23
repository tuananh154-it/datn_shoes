<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product'; 
    protected $primaryKey = 'id';
    public $timestamps = true; 
    
    protected $fillable = [
        'name', 'description', 'category_id', 'brand_id', 'price', 'discount_price', 'image_url', 'status'
    ];

    public function productdetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }
}
