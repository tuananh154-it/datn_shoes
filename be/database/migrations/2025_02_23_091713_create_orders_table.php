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
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->decimal('deliver_fee', 15, 2);
            $table->foreignId('customer_id')->constrained('customers');
            $table->decimal('total_amount', 15, 2);
            $table->string('address');
            $table->string('phone_number');
            $table->string('email');
            $table->decimal('total_price', 15, 2);
            $table->text('note')->nullable();
            $table->timestamps();
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
