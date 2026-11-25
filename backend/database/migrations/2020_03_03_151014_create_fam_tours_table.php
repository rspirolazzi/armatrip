<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fam_tours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id', 50)->index();
            $table->string('subject')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('from')->nullable();
            $table->string('flags')->nullable();
            $table->string('folder')->nullable();
            $table->text('body')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('student')->nullable();
            $table->string('school')->nullable();
            $table->string('city')->nullable();
            $table->text('link_google_calendar')->nullable();
            $table->string('category')->nullable();
            $table->boolean('moved')->default(0);
            $table->dateTime('calendar')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->dateTime('last_modification')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique('message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
