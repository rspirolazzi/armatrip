<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterOrderYearNullTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // "orders" tiene una columna ENUM, que Doctrine no reconoce al usar change().
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('year')->nullable()->change();
            $table->integer('biweekly')->nullable()->change();
            $table->integer('month')->nullable()->change();
            $table->integer('qty_adults')->default(0);
            $table->integer('qty_children')->default(0);
            $table->integer('qty_babies')->default(0);
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // "year", "biweekly" y "month" quedan opcionales: puede haber filas sin valor.
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['qty_adults', 'qty_children', 'qty_babies', 'date_start', 'date_end']);
        });
    }
}
