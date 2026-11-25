<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
             $table->renameColumn('email','phone');
             $table->dropColumn('store_id');
             $table->string('store_code',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
            $table->integer('store_id')->unsigned();
             $table->renameColumn('phone','email');
             $table->dropColumn('store_code');
        });
    }
}
