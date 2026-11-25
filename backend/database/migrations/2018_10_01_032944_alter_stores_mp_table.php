<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStoresMpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('mp_public_key', 255)->nullable();
            $table->string('mp_access_token', 255)->nullable();
            $table->boolean('mp_enabled_test')->default(0);
            $table->string('mp_public_key_test', 255)->nullable();
            $table->string('mp_access_token_test', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
