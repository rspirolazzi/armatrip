<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPackagesAddBackgroundImageAndVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->text('background_image')
                ->after('active')
                ->nullable();
            $table->text('video_link')
                ->after('active')
                ->nullable();
            $table->boolean('shareable')
                ->default(1)
                ->after('active');
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
