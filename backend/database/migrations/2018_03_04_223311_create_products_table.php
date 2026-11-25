<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('name', 255);
            $table->string('code');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('description', 1024)->nullable();
            $table->string('image_primary', 1024)->nullable();
            $table->string('image_secondary', 1024)->nullable();
            $table->string('image_tertiary', 1024)->nullable();
            $table->string('icon', 255)->nullable();
            $table->text('tags')->nullable();
            $table->decimal('price',20,2);
            $table->integer('order')->default(0);
            $table->integer('min')->default(0);
            $table->integer('max')->default(9999);
            $table->integer('category_id')->unsigned();
            $table->boolean('active')->default(1);
            $table->boolean('allowed_to_sell')->default(0);
            $table->boolean('default')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique('code');
            $table->foreign('category_id')->references('id')->on('categories');
        });
        /*Schema::create('products', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('products');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('products');
        Schema::enableForeignKeyConstraints();
    }
}
