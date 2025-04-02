<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Bảng bình luận (comments)
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Hỗ trợ bình luận cha - con
            $table->text('content');
            $table->unsignedBigInteger('number_of_likes')->default(0); // Số lượt thích
            $table->boolean('is_anonymous')->default(false); // Bình luận ẩn danh
            $table->boolean('is_edited')->default(false); // Đánh dấu bình luận đã chỉnh sửa
            $table->softDeletes(); // Thêm trường deleted_at để hỗ trợ xóa mềm
            $table->timestamps();
        });

        // Bảng đánh giá (reviews)
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Chỉ cho phép đánh giá khi đơn hàng hoàn thành
            $table->tinyInteger('rating')->default(1); // Đánh giá 1-5 sao
            $table->text('content');
            $table->text('reply')->nullable(); // Phản hồi của người bán
            $table->tinyInteger('service')->default(1); // Đánh giá dịch vụ 1-5 sao
            $table->tinyInteger('packaging')->default(1); // Đánh giá đóng gói 1-5 sao
            $table->tinyInteger('shipping')->default(1); // Đánh giá vận chuyển 1-5 sao
            $table->tinyInteger('customer_service')->default(1); // Đánh giá dịch vụ khách hàng 1-5 sao
            $table->boolean('is_anonymous')->default(false); // Đánh giá ẩn danh
            $table->unsignedInteger('helpful_count')->default(0); // Số lượt hữu ích
            $table->boolean('is_edited')->default(false); // Đánh dấu đánh giá đã chỉnh sửa
            $table->boolean('is_replied')->default(false); // Đánh dấu đánh giá đã được phản hồi
            $table->boolean('is_reported')->default(false); // Đánh dấu đánh giá đã bị báo cáo
            $table->softDeletes(); // Thêm trường deleted_at để hỗ trợ xóa mềm
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('comments');
    }
};