<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique(25);
            $table->string('title')->nullable();
            $table->text('message');
            $table->integer('duration')->default(0);
            $table->enum('type', ['success','warning','danger','info'])->nullable();
            $table->boolean('is_custom')->default(0);
            $table->boolean('is_actions')->default(0);
            $table->integer('order')->default(0);
            $table->string('uri')->nullable();
            $table->text('rules')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notifications');
    }
}
