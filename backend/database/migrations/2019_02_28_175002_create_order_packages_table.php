<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderPackagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_packages', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->integer('package_id')->unsigned();
            $table->decimal('price',20,2);
            $table->integer('qty');
            $table->decimal('total',20,2);
            $table->integer('order_id')->unsigned();
            $table->decimal('tax', 10,2)->nullable();
            $table->integer('installment')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('order_items');
        Schema::enableForeignKeyConstraints();
    }
}
