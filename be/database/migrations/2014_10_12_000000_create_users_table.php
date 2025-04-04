<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });

        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->string('gender')->nullable();
        //     $table->date('date_of_birth')->nullable();
        //     $table->string('address')->nullable();
        //     $table->string('phone_number')->nullable();
        //     $table->rememberToken();
        //     $table->string('phone_number')->nullable();
        //     $table->text('address')->nullable();
        //     $table->date('dob')->nullable();
        //     $table->enum('gender', ['male', 'female', 'other'])->nullable();  // Thêm giới tính
        //     $table->timestamps();
        // });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('gender')->nullable();  // Giới tính
            $table->date('date_of_birth')->nullable();  // Ngày sinh
            $table->string('address')->nullable();  // Địa chỉ
            $table->string('phone_number')->nullable();  // Số điện thoại
            $table->rememberToken();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
