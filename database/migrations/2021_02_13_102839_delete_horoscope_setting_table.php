<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteHoroscopeSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('horoscope_setting');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('horoscope_setting', function (Blueprint $table) {
            $table->increments('horoscope_setting_id');
            $table->string('zodiac');
            $table->string('parse_url', 500);
            $table->string('short_description_parse_url', 500)->nullable();
            $table->longText('template')->nullable();
            $table->timestamp('send_time');
            $table->timestamps();
            $table->integer('template_video_id')->nullable();
        });
    }
}
