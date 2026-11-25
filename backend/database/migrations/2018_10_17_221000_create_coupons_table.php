<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('coupons.table', 'coupons'), function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');

            $table->string('code', 32);
            $table->string('name', 75);
            $table->string('description', 255)->nullable();
            $table->double('reward', 10, 2)->nullable();
            $table->text('data')->nullable();
            $table->boolean('is_disposable')->default(false);
            $table->boolean('enabled')->default(true);
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->time('hs_start_at')->nullable();
            $table->time('hs_end_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('code');
        });

        Schema::create(config('coupons.relation_table', 'coupon_user'), function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('coupon_id');
            $table->unsignedInteger('order_id')->nullable();;

            $table->timestamp('used_at');

            //$table->primary(['user_id', 'coupon_id', 'order_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('coupon_id')->references('id')->on(config('coupons.table', 'coupons'));
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
        Schema::dropIfExists(config('coupons.relation_table', 'coupon_user'));
        Schema::dropIfExists(config('coupons.table', 'coupons'));
        Schema::enableForeignKeyConstraints();
    }
}
