<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{

    use HasFactory;
    protected $table = 'color';
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
