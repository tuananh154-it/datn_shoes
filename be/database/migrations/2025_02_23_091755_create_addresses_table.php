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
    Schema::create('addresses', function (Blueprint $table) {
        $table->id();
        // $table->foreignId('customer_id')->constrained('customers');
        $table->foreignId('user_id')->constrained('users');
        $table->string('city');
        $table->string('district');
        $table->string('ward');
        $table->string('name');
        $table->string('phone_number');
        $table->text('addressDetail');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
