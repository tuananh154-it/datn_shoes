<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Tạo quyền
        Permission::create(['name' => 'edit_posts']);
        Permission::create(['name' => 'delete_posts']);

        // Tạo vai trò và gán quyền cho vai trò
        $adminRole = Role::firstOrCreate(['name' => 'admin']); // Kiểm tra vai trò admin đã tồn tại chưa
        $adminRole->givePermissionTo('edit_posts');
        $adminRole->givePermissionTo('delete_posts');

        // Nếu bạn muốn tạo thêm vai trò khác:
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->givePermissionTo('edit_posts');
    }
}
