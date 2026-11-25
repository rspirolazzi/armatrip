<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MercadoPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('mercado_pagos', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->decimal('transaction_amount',20,2);
            $table->string('token', 1024);
            $table->string('description', 1024)->nullable();
            $table->string('payment_method_id')->nullable();
            $table->string('status')->nullable();
            $table->longText('body')->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('mercado_pagos');
        Schema::enableForeignKeyConstraints();
    }
}
