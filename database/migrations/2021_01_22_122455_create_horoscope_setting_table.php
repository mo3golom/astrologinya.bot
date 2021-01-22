<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoroscopeSettingTable extends Migration
{
    private const ZODIAC = [
        'Овен',
        'Телец',
        'Близнецы',
        'Рак',
        'Лев',
        'Дева',
        'Весы',
        'Скорпион',
        'Стрелец',
        'Козерог',
        'Водолей',
        'Рыбы',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horoscope_setting', function (Blueprint $table) {
            $table->increments('horoscope_setting_id');
            $table->enum('zodiac', self::ZODIAC);
            $table->string('parse_url', 500);
            $table->string('short_description_parse_url', 500)->nullable();
            $table->longText('template')->nullable();
            $table->timestamp('send_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horoscope_setting');
    }
}
