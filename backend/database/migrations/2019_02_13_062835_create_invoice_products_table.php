<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->unsignedInteger('invoice_id');
            $table->string('model_type');
            $table->integer('model_id');
            $table->integer('qty')->default(0);
            $table->decimal('price', 20, 2)->default(0);
            $table->decimal('total', 20, 2)->default(0);
            $table->string('extra_1')->nullable();
            $table->string('extra_2')->nullable();
            $table->string('extra_3')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');
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
        Schema::dropIfExists('invoice_items');
        Schema::enableForeignKeyConstraints();
    }
}
