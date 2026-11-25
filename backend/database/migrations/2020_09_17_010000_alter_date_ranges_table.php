<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterDateRangesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_date_ranges', function (Blueprint $table) {
            $table->date('date_end')->before('year')->nullable();
            $table->date('date_start')->before('year')->nullable();
        });
        Schema::table('date_ranges', function (Blueprint $table) {
            $table->date('date_end')->before('year')->nullable();
            $table->date('date_start')->before('year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['package_date_ranges', 'date_ranges'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropColumn(['date_start', 'date_end']);
            });
        }
    }
}
