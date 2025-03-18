<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
    
    protected $table = 'roles'; // Đảm bảo đúng tên bảng

    public $timestamps = false; // Tắt timestamps nếu bảng roles không có created_at và updated_at

    /**
     * Quan hệ nhiều-nhiều với User
     * Một role có thể thuộc nhiều user
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id');
    }
}
