<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name', 'description', 'category_id', 'brand_id', 'price', 'image', 'status'
    ];

    public function productdetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
