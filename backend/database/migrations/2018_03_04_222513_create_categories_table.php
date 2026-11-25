<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('name', 255);
            $table->string('code');
            $table->integer('parent_id')->nullable();
            $table->string('image_primary', 1024)->nullable();
            $table->string('image_secondary', 1024)->nullable();
            $table->string('image_tertiary', 1024)->nullable();
            $table->boolean('required')->default(1);
            $table->boolean('active')->default(1);
            $table->boolean('multiple')->default(0);
            $table->integer('order')->default(0);
            $table->unique('code');
            $table->timestamps();
            $table->softDeletes();
            //$table->foreign('parent_id')->references('id')->on('categories');
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
        Schema::dropIfExists('categories');
        Schema::enableForeignKeyConstraints();
    }
}
