<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;

class UpdateGroupForShowReviews extends Migration
{
    public function up(): void
    {
        // Chỉ cần 1 quyền xem
        Permission::firstOrCreate(
            ['name' => 'show-reviews'],
            ['guard_name' => 'web', 'group' => 'role:reviews']
        );
    }

    public function down(): void
    {
        // Nếu rollback thì xoá group hoặc xoá luôn quyền tuỳ bạn
        Permission::where('name', 'show-reviews')
            ->update(['group' => null]);   // hoặc ->delete();
    }
}