<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablleHoroscopeChangeVideoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horoscope', function (Blueprint $table) {
            $table->dropColumn('video_id');
            $table->string('video_url', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horoscope', function (Blueprint $table) {
            $table->dropColumn('video_url');
            $table->integer('video_id')->nullable();
        });
    }
}
