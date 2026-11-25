<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('store_id');
            $table->boolean('active')->default(1);
            $table->text('description')->nullable();
            $table->decimal('price',20,2);
            $table->decimal('price_since',20,2)->nullable();
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->string('tags')->nullable();
            $table->string('code');
            $table->decimal('tax',10,2)->default(0);
            $table->integer('installment')->default(12);
            $table->timestamps();
            $table->softDeletes();
            $table->unique('code');
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('packages');
        Schema::enableForeignKeyConstraints();
    }
}
