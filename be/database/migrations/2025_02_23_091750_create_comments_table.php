<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('comments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // ID của người dùng đã tạo bình luận
        $table->unsignedBigInteger('product_id'); // ID của sản phẩm
        $table->string('comment')->nullable(); // Nội dung bình luận
        $table->string('file')->nullable(); // Đường dẫn tới file đính kèm
        $table->unsignedTinyInteger('star_rating')->default(0); // Đánh giá sao, giá trị từ 0-5
        $table->softDeletes(); // Cột dùng cho soft delete
        $table->timestamps(); // Cột thời gian tạo và cập nhật

        // Khóa ngoại liên kết với bảng users và products
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
