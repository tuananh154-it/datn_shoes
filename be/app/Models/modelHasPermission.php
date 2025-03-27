<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    protected $table = 'model_has_permissions';

    public $timestamps = false;

    protected $fillable = [
        'model_type',
        'model_id',
        'permission_id'
    ];

    // Quan hệ với bảng permissions
    public function permission()
    {
        return $this->belongsTo(\App\Models\Permission::class, 'permission_id');
    }
}
