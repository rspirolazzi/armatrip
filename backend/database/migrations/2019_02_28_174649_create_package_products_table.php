<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackageProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_products', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->integer('package_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->boolean('required')->default(0);
            $table->boolean('default')->default(0);
            $table->decimal('price',20,2)->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('package_products');
        Schema::enableForeignKeyConstraints();
    }
}
