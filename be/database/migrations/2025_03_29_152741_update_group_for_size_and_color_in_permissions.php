<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

class UpdateGroupForSizeAndColorInPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Cập nhật giá trị 'group' cho các quyền liên quan đến size
        Permission::whereIn('name', ['show-sizes', 'edit-size', 'delete-size', 'create-size'])
            ->update(['group' => 'role:sizes']);

        // Cập nhật giá trị 'group' cho các quyền liên quan đến color
        Permission::whereIn('name', ['show-colors', 'edit-color', 'delete-color', 'create-color'])
            ->update(['group' => 'role:colors']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Xóa giá trị 'group' nếu cần rollback
        Permission::whereIn('name', ['show-sizes', 'edit-size', 'delete-size', 'create-size'])
            ->update(['group' => null]);

        Permission::whereIn('name', ['show-colors', 'edit-color', 'delete-color', 'create-color'])
            ->update(['group' => null]);
    }
}
