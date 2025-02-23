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
    Schema::create('product_details', function (Blueprint $table) {
        $table->id();
        $table->string('image')->nullable();
        $table->foreignId('product_id')->constrained('products');
        $table->foreignId('size_id')->constrained('sizes');
        $table->foreignId('color_id')->constrained('colors');
        $table->integer('quantity');
        $table->decimal('default_price', 15, 2);
        $table->decimal('discount_price', 15, 2)->nullable();
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
