<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id'); // người tạo request (khách hàng)
            $table->string('reason');
            $table->text('description')->nullable(); // Mô tả chi tiết lý do trả hàng
            $table->string('image')->nullable(); // Hình ảnh chứng minh lý do trả hàng
            $table->enum('status', ['pending', 'reviewed', 'approved', 'rejected'])->default('pending');

            // Số tài khoản
            $table->string('bank_account'); // Số tài khoản ngân hàng

            $table->unsignedBigInteger('reviewed_by')->nullable(); // nhân viên CSKH
            $table->unsignedBigInteger('admin_id')->nullable();    // người duyệt cuối cùng

            $table->timestamp('requested_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('admin_approved_at')->nullable();

            $table->timestamp('return_received_at')->nullable(); // Thời gian nhận hàng trả lại

            $table->text('staff_notes')->nullable(); // Ghi chú nội bộ CSKH

            $table->timestamps();

            // Foreign keys (tuỳ chọn nếu bạn đã có bảng users & orders)
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
