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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 255)->nullable();
            $table->decimal('cost',20 ,4)->nullable();
            $table->string('currency_type', 20)->default('jpy');
            $table->string('note', 255)->nullable();
            $table->string('invoice', 255)->nullable();
            $table->string('pay', 255)->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('exchange_rate_id');
            $table->dateTime('payment_date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('exchange_rate_id')->references('id')->on('exchange_rates');
            $table->enum('currency_type', ['jpy', 'usd'])->default('jpy')->change();
       
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
