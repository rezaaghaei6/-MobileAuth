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
    Schema::create('admin_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('admin_id')->nullable(); // ادمینی که این کارو انجام داده
        $table->string('action'); // عمل انجام شده
        $table->text('message')->nullable(); // توضیحات
        $table->ipAddress('ip')->nullable(); // آی‌پی
        $table->timestamps();

        $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
