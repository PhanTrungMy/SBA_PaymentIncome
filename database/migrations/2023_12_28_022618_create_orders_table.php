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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('company_name', 255);
            $table->decimal('jpy', 20, 4)->nullable();
            $table->decimal('usd', 20, 4)->nullable();
            $table->decimal('vnd', 20, 4)->nullable();
            $table->unsignedBigInteger('exchange_rate_id');
            $table->dateTime('order_date')->nullable();
            $table->timestamps();
            $table->softDeletes(); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('exchange_rate_id')->references('id')->on('exchange_rates');
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
