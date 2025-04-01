<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

class UpdateGroupInPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Cập nhật giá trị 'group' cho các quyền có sẵn
        Permission::whereIn('name', ['show-products', 'edit-product', 'delete-product', 'create-product'])
            ->update(['group' => 'role:products']);

        Permission::whereIn('name', ['show-orders', 'edit-order', 'delete-order', 'create-order'])
            ->update(['group' => 'role:orders']);

        Permission::whereIn('name', ['show-users', 'edit-user', 'delete-user', 'create-user'])
            ->update(['group' => 'role:users']);

        Permission::whereIn('name', ['show-articles', 'edit-article', 'delete-article', 'create-article'])
            ->update(['group' => 'role:articles']);

        Permission::whereIn('name', ['show-comments', 'edit-comment', 'delete-comment', 'create-comment'])
            ->update(['group' => 'role:comments']);

        Permission::whereIn('name', ['show-categories', 'edit-category', 'delete-category', 'create-category'])
            ->update(['group' => 'role:categories']);

        Permission::whereIn('name', ['show-brands', 'edit-brand', 'delete-brand', 'create-brand'])
            ->update(['group' => 'role:brands']);

        Permission::whereIn('name', ['show-banners', 'edit-banner', 'delete-banner', 'create-banner'])
            ->update(['group' => 'role:banners']);

        Permission::whereIn('name', ['show-vouchers', 'edit-voucher', 'delete-voucher', 'create-voucher'])
            ->update(['group' => 'role:vouchers']);

        Permission::whereIn('name', ['show-contacts', 'edit-contact', 'delete-contact', 'create-contact'])
            ->update(['group' => 'role:contacts']);

        Permission::whereIn('name', ['show-roles', 'edit-role', 'delete-role', 'create-role'])
            ->update(['group' => 'role:roles']);

        Permission::whereIn('name', ['show-dashboards'])
            ->update(['group' => 'role:dashboards']);

        // Cập nhật giá trị 'group' cho các quyền liên quan đến size
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nếu cần rollback, bạn có thể xóa cột 'group'
        Permission::whereIn('name', ['show-products', 'edit-product', 'delete-product', 'create-product'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-orders', 'edit-order', 'delete-order', 'create-order'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-users', 'edit-user', 'delete-user', 'create-user'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-articles', 'edit-article', 'delete-article', 'create-article'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-comments', 'edit-comment', 'delete-comment', 'create-comment'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-categories', 'edit-category', 'delete-category', 'create-category'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-brands', 'edit-brand', 'delete-brand', 'create-brand'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-banners', 'edit-banner', 'delete-banner', 'create-banner'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-vouchers', 'edit-voucher', 'delete-voucher', 'create-voucher'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-contacts', 'edit-contact', 'delete-contact', 'create-contact'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-roles', 'edit-role', 'delete-role', 'create-role'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-dashboards'])
            ->update(['group' => null]);
    }
}
