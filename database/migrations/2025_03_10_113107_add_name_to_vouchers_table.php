<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('name')->after('id'); // Thêm cột name sau cột id
        });
    }

    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('name'); // Xóa cột name nếu rollback
        });
    }
};
