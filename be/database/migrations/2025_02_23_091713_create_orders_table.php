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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers');
            $table->enum('status', [
                'waiting_for_confirmation',
                'waiting_for_pickup',
                'waiting_for_delivery',
                'delivered',
                'returned',
                'cancelled'
            ]);
            $table->decimal('deliver_fee', 15, 2)->nullable();
            // $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('payment_status', ['paid', 'unpaid', 'pending', 'failed'])->default('unpaid');
            $table->enum('payment_method', ['cash_on_delivery', 'momo', 'zalopay']);

            $table->string('address');
            $table->string('phone_number');
            $table->string('email');
            $table->decimal('total_price', 15, 2);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
