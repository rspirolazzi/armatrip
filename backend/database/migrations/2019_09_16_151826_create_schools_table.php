<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchoolsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->string('sector')->nullable();
            $table->string('sub_sector')->nullable();
            $table->string('province')->nullable();
            $table->string('department')->nullable();
            $table->string('city')->nullable();
            $table->string('city_code')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone_area')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('email', 1025)->nullable();
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
        Schema::drop('schools');
    }
}
