<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $roles = [
                'web',
                'store',
                'admin',
            ];

            $table->increments('id');
            $table->string('name');
            //$table->string('last_name')->nullable();
            $table->string('phone_code', 5)->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->string('telephone')->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('facebookID')->nullable();
            $table->enum('role', $roles)->default($roles[0]);
            $table->boolean('is_verified')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
        /* Comenatado por problema al ejecutar migraciones
        Schema::create('users', function (Blueprint $table) {
            $table->index('facebookID');
        });
        */

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
}
