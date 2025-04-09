<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Thêm cột 'group' vào bảng 'permissions'
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('group')->nullable();  // Cột 'group' sẽ lưu tên nhóm quyền
        });

        // Cập nhật giá trị 'group' cho các quyền có sẵn

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Xóa cột 'group' nếu cần rollback migration
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }
};
