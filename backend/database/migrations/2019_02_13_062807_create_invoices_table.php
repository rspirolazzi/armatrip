<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('store_id');
            $table->enum('status', ['draft', 'confirmed', 'received'])->default('draft');
            $table->binary('file')->nullable();
            $table->string('model_type');
            $table->text('comment')->nullable();
            $table->text('order_number')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::drop('internal_orders');
    }
}
