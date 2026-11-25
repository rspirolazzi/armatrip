<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('store_id')->unsigned();
            $table->string('number', 20);
            $table->string('comments', 1024)->nullable();
            $table->decimal('sub_total',20,2);
            $table->decimal('total',20,2);
            $table->integer('year');
            $table->integer('month');
            $table->integer('biweekly');
            $table->string('school_name')->nullable();
            $table->enum('status', ['pending', 'cancelled', 'finished', 'sold', 'contacted']);
            $table->timestamps();
            $table->softDeletes();
            $table->unique('number');
        });

        Schema::table('orders', function ($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('active_order_id')
                ->after('is_verified')
                ->nullable();
            $table->foreign('active_order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('SET NULL')
            ;
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
        Schema::dropIfExists('orders');
        Schema::enableForeignKeyConstraints();
    }
}
