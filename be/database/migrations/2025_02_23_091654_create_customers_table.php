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
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('username')->unique();
        $table->string('password');
        $table->string('phone_number');
        $table->foreignId('reset_request_id')->nullable()->constrained('password_reset_requests');
        $table->string('email');
        $table->text('address');
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->date('date_of_birth');
        $table->string('name');
        $table->text('notes')->nullable();
        $table->enum('gender', ['male', 'female', 'other']);
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
