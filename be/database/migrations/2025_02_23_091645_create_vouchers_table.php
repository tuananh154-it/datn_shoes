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
    Schema::create('vouchers', function (Blueprint $table) {
        $table->id();
        $table->decimal('discount_amount', 15, 2)->nullable();
        $table->decimal('discount_percent', 5, 2)->nullable();
        $table->date('expiration_date');
        $table->decimal('min_purchase_amount', 15, 2);
        $table->decimal('max_discount_amount', 15, 2);
        $table->text('terms_and_conditions')->nullable();
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
