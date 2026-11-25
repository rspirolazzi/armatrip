<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('name', 255);
            $table->string('code', 20);
            $table->string('email')->nullable();
            $table->string('telephone', 255)->nullable();
            $table->text('tags')->nullable();
            $table->text('icon')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->unique('code');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('store_id')->nullable()->unsigned();
            $table->foreign('store_id')->references('id')->on('stores');
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
        Schema::dropIfExists('stores');
        Schema::enableForeignKeyConstraints();
    }
}
