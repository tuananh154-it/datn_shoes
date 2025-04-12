<?php

// database/migrations/2025_04_09_create_returns_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration
{
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained(); // liên kết với bảng đơn hàng
            $table->foreignId('product_id')->constrained(); // liên kết với bảng sản phẩm
            $table->integer('quantity_returned');
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('returns');
    }
}