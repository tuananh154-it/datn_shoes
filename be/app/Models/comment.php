<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'product_id',
        'comment',
        'file',
        'star_rating'
    ];
    protected $datas = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Định nghĩa mối quan hệ với Product (sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
//     public function productVariant()
// {
//     return $this->belongsTo(ProductVariant::class);
// }

}
