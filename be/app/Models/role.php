<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Role extends ModelsRole
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
