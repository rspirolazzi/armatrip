<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackageDateRangesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_date_ranges', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->unsignedInteger('package_id');
            $table->string('name')->nullable();
            $table->integer('year');
            $table->integer('month')->nullable();
            $table->integer('biweekly')->nullable();
            $table->decimal('tax', 10,2)->default(0);
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('date_ranges');
    }
}
