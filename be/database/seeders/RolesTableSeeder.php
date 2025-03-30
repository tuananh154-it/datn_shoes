<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'super-admin', 'guard_name' => 'web'],  // Đã đổi từ super-admin thành superadmin
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'employee', 'guard_name' => 'web'],
            ['name' => 'manager', 'guard_name' => 'web'],
            ['name' => 'user', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate($role);
        }

        $superAdmin = User::whereEmail('admin@gmail.com')->first();

        if (!$superAdmin) {
            $superAdmin = User::factory()->create([
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
            ]);
        }
        // Thay đổi ở đây: đổi super-admin thành superadmin
        $superAdmin->assignRole('super-admin');

        $permissions = [
            ['name' => 'show-products', 'guard_name' => 'web'],
            ['name' => 'show-colors', 'guard_name' => 'web'],
            ['name' => 'show-sizes', 'guard_name' => 'web'],
            ['name' => 'show-users', 'guard_name' => 'web'],
            ['name' => 'show-orders', 'guard_name' => 'web'],
            ['name' => 'show-articles', 'guard_name' => 'web'],
            ['name' => 'show-comments', 'guard_name' => 'web'],
            ['name' => 'show-contacts', 'guard_name' => 'web'],
            ['name' => 'show-banners', 'guard_name' => 'web'],
            ['name' => 'show-vouchers', 'guard_name' => 'web'],
            ['name' => 'show-categories', 'guard_name' => 'web'],
            ['name' => 'show-brands', 'guard_name' => 'web'],
            ['name' => 'show-roles', 'guard_name' => 'web'],
            ['name' => 'show-dashboards', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $item) {
            Permission::updateOrCreate($item);
        }
    }
}
