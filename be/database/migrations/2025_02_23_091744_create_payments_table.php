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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained('orders');
        $table->foreignId('user_id')->constrained('users'); 
        $table->string('note')->nullable(); 
        $table->dateTime('payment_date');
        $table->enum('payment_method', ['cash_on_delivery', 'momo', 'zalopay']);
        $table->decimal('amount', 15, 2);
        $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
        $table->timestamps();
        $table->softDeletes();
    });
    
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
